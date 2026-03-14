<?php

namespace App\Repositories;


use Illuminate\Validation\ValidationException;
use App\Traits\Validate;
use App\Models\Items;
use App\Models\ReceiptInvoiceItems;

class PurchaseReturnRepository
{
    use Validate;
    public function createOrUpdateItems($purchaseReturn, $data)
    {
        // Handle Create Items
        $ids = [];
        foreach ($data['items'] as $item) {
            $this->ensureSellingPriceNotBelowCost($item);

            $this->validationQuantity($item['quantity'], $item['ri_quantity']);
            $relatedItem = $this->existsWhereId(new Items(), $item['item_id']);

            if ($item['detail_id']) {
                $detail = $purchaseReturn->items()->where('id', $item['detail_id'])->firstOrFail();

                if ($detail) {
                    $detail->update(array_merge($item, [
                        'item_name' => $relatedItem->name,
                        'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                    ]));
                }

                $ids[] = $detail->id;
            } else {

                $detail = $purchaseReturn->items()->create(array_merge($item, [
                    'coa_id' => 1,
                    'sales_return_id' => $purchaseReturn->id,
                    'item_name' => $relatedItem->name,
                    'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                ]));


                $ids[] = $detail->id;
            }
        }

        $purchaseReturn->items()->whereNotIn('id', $ids)->delete();


        $purchaseReturn = $purchaseReturn->fresh();

        if ($purchaseReturn->status >= 2) {
            // Handle Payment Amount
            $this->ensureInvoicePaidIn($purchaseReturn);

            // Handle quantity
            $purchaseReturn = $this->applyReceiptInvoiceQuantity($purchaseReturn);

            // Handle Status
            $this->refreshStatus($purchaseReturn);
        }

        $this->settingJournal($purchaseReturn);
    }

    public function deleteItems($purchaseReturn)
    {
        $this->settingJournal($purchaseReturn, 'delete');
        if ($purchaseReturn->status >= 2) {

            // Remove Quantity
            $this->applyReceiptInvoiceQuantity($purchaseReturn, 'delete');

            // Change status
            $this->refreshStatus($purchaseReturn);
        }

        if ($purchaseReturn->items()) {
            $purchaseReturn->items()->delete();
        }
    }

    protected function validationQuantity($quantity, $maxQuantity)
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException(
                'Quantity tidak boleh 0 atau kurang'
            );
        }

        if ($quantity > $maxQuantity) {
            throw new \InvalidArgumentException(
                'Quantity tidak boleh melebihi RI quantity '
            );
        }

        return true;
    }

    protected function ensureInvoicePaidIn($purchaseReturn)
    {

        if ((float) $purchaseReturn->grand_total < $purchaseReturn->paid_amount) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Jumlah uang pengembalian lebih dari total transaksi'
            ]);
        }

        if ($purchaseReturn->paid_amount <= 0) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Jumlah uang pengembalian setidaknya tidak 0'
            ]);
        }
    }


    protected function ensureSellingPriceNotBelowCost($item)
    {
        $salePrice = ($item['sale_price'] * $item['quantity']);
        $purchasePrice = $item['purchase_price'] * $item['quantity'];
        if ($salePrice < $purchasePrice) {
            throw ValidationException::withMessages([
                'margin' => 'Harga jual barang ' . $item['name'] . ' tidak boleh lebih kecil dari harga beli.',
            ]);
        }
    }

    protected function refreshStatus($purchaseReturn)
    {
        $receiptInvoice = $purchaseReturn->receiptInvoice;

        if (!$receiptInvoice) {
            throw ValidationException::withMessages([
                'sales_invoice_id' => 'Transaksi Invoice Penjualan tidak ditemukan.',
            ]);
        }

        // Check Quantity and items
        $items = $receiptInvoice->items;

        if ($items->every(fn($i) => $i->purchase_return_items_quantity == 0)) {
            $status = 2;
        } elseif ($items->every(fn($i) => $i->purchase_return_items_quantity == $i->quantity)) {
            $status = 6;
        } else {
            $status = 3;
        }

        $receiptInvoice->update([
            'status' => $status,
        ]);
    }

    protected function applyReceiptInvoiceQuantity($purchaseReturn, $method = 'create')
    {

        $purchaseReturn->items->each(function ($item) use ($method) {
            $receiptInvoice = ReceiptInvoiceItems::findOrFail($item->receipt_invoice_items_id);

            if (!$receiptInvoice) {
                throw ValidationException::withMessages([
                    'receipt_invoice_items_id' => 'barang ' . $item['name'] . ' tidak ditemukan.',
                ]);
            }

            $prQuantity = 0;
            switch ($method) {
                case 'create':
                    $prQuantity = $item->quantity;
                    break;
                case 'delete':
                    $prQuantity = 0;
                    break;
                default:
                    $prQuantity = $item->quantity;
                    break;
            }


            $receiptInvoice->update([
                'purchase_return_items_quantity' => $prQuantity
            ]);

            $receiptInvoice = $receiptInvoice->fresh();

            if ($receiptInvoice->purchase_return_items_quantity > $receiptInvoice->quantity) {
                throw ValidationException::withMessages([
                    'sales_invoice_items_id' => 'barang ' . $item['name'] . ' sudah melebihi batas quantity di uang muka.',
                ]);
            }
        });

        return $purchaseReturn->fresh();
    }


    public function settingJournal($purchaseReturn, $method = 'create')
    {
        $journal = app(JournalRepository::class);

        switch ($method) {
            case 'create':
                // return penjualan debit
                // kas / bank credit
                $journal->generateJournal(
                    data: $purchaseReturn,
                    details: $purchaseReturn->items,
                    module: 'purchase-return',
                    action: 'purchase_return',
                    columnPaymentMethod: 'payment_method',
                    columnContact: 'customer',
                    columnDescription: 'description',
                    columnNominalDebit: 'paid_amount',
                    columnNominalCredit: 'grand_total'
                );


                break;
            case 'delete':
                $journal->destroyJournal($purchaseReturn);
                break;
            default:
                break;
        }
    }
}
