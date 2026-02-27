<?php

namespace App\Repositories;


use Illuminate\Validation\ValidationException;
use App\Traits\Validate;
use App\Models\Items;
use App\Models\SalesInvoiceItems;

class SalesReturnRepository
{
    use Validate;
    public function createOrUpdateItems($salesReturn, $data)
    {
        // Handle Create Items
        $ids = [];
        foreach ($data['items'] as $item) {
            $this->ensureSellingPriceNotBelowCost($item);

            $this->validationQuantity($item['quantity'], $item['si_quantity']);
            $relatedItem = $this->existsWhereId(new Items(), $item['item_id']);

            if ($item['detail_id']) {
                $detail = $salesReturn->items()->where('id', $item['detail_id'])->firstOrFail();

                if ($detail) {
                    $detail->update(array_merge($item, [
                        'item_name' => $relatedItem->name,
                        'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                    ]));
                }

                $ids[] = $detail->id;
            } else {

                $detail = $salesReturn->items()->create(array_merge($item, [
                    'coa_id' => 1,
                    'sales_return_id' => $salesReturn->id,
                    'item_name' => $relatedItem->name,
                    'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                ]));


                $ids[] = $detail->id;
            }
        }

        $salesReturn->items()->whereNotIn('id', $ids)->delete();


        $salesReturn = $salesReturn->fresh();

        if ($salesReturn->status >= 2) {
            // Handle Payment Amount
            $this->ensureInvoicePaidIn($salesReturn);

            // Handle quantity
            $salesReturn = $this->applySalesInvoiceQuantity($salesReturn);

            // Handle Status
            $this->refreshStatus($salesReturn);
        }

        $this->settingJournal($salesReturn);
    }

    public function deleteItems($salesReturn)
    {
        $this->settingJournal($salesReturn, 'delete');
        if ($salesReturn->status >= 2) {

            // Remove Quantity
            $this->applySalesInvoiceQuantity($salesReturn, 'delete');

            // Change status
            $this->refreshStatus($salesReturn);
        }

        if ($salesReturn->items()) {
            $salesReturn->items()->delete();
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
                'Quantity tidak boleh melebihi SI quantity '
            );
        }

        return true;
    }

    protected function ensureInvoicePaidIn($salesReturn)
    {

        if ((float) $salesReturn->grand_total < $salesReturn->paid_amount) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Jumlah uang pengembalian lebih dari total transaksi'
            ]);
        }

        if ($salesReturn->paid_amount <= 0) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Jumlah uang pengembalian setidaknya tidak 0'
            ]);
        }
    }


    protected function ensureSellingPriceNotBelowCost($item)
    {
        $salePrice = ($item['sale_price'] * $item['quantity']) + $item['service'];
        $purchasePrice = $item['purchase_price'] * $item['quantity'];
        if ($salePrice < $purchasePrice) {
            throw ValidationException::withMessages([
                'margin' => 'Harga jual barang ' . $item['name'] . ' tidak boleh lebih kecil dari harga beli.',
            ]);
        }
    }

    protected function refreshStatus($salesReturn)
    {
        $salesInvoice = $salesReturn->salesInvoice;

        if (!$salesInvoice) {
            throw ValidationException::withMessages([
                'sales_invoice_id' => 'Transaksi Invoice Penjualan tidak ditemukan.',
            ]);
        }

        // Check Quantity and items
        $items = $salesInvoice->items;

        if ($items->every(fn($i) => $i->sales_return_items_quantity == 0)) {
            $status = 2;
        } elseif ($items->every(fn($i) => $i->sales_return_items_quantity == $i->quantity)) {
            $status = 6;
        } else {
            $status = 3;
        }

        $salesInvoice->update([
            'status' => $status,
        ]);
    }

    protected function applySalesInvoiceQuantity($salesReturn, $method = 'create')
    {

        $salesReturn->items->each(function ($item) use ($method) {
            $salesInvoices = SalesInvoiceItems::findOrFail($item->sales_invoice_items_id);

            if (!$salesInvoices) {
                throw ValidationException::withMessages([
                    'sales_invoice_items_id' => 'barang ' . $item['name'] . ' tidak ditemukan.',
                ]);
            }

            $srQuantity = 0;
            switch ($method) {
                case 'create':
                    $srQuantity = $item->quantity;
                    break;
                case 'delete':
                    $srQuantity = 0;
                    break;
                default:
                    $srQuantity = $item->quantity;
                    break;
            }


            $salesInvoices->update([
                'sales_return_items_quantity' => $srQuantity
            ]);

            $salesInvoices = $salesInvoices->fresh();

            if ($salesInvoices->sales_return_items_quantity > $salesInvoices->quantity) {
                throw ValidationException::withMessages([
                    'sales_invoice_items_id' => 'barang ' . $item['name'] . ' sudah melebihi batas quantity di uang muka.',
                ]);
            }
        });

        return $salesReturn->fresh();
    }


    public function settingJournal($salesReturn, $method = 'create')
    {
        $journal = app(JournalRepository::class);

        switch ($method) {
            case 'create':
                // return penjualan debit
                // kas / bank credit
                $journal->generateJournal(
                    data: $salesReturn,
                    details: $salesReturn->items,
                    module: 'sales-return',
                    action: 'sales_return',
                    columnPaymentMethod: 'payment_method',
                    columnContact: 'customer',
                    columnDescription: 'description',
                    columnNominalDebit: 'grand_total',
                    columnNominalCredit: 'paid_amount'
                );

                $salesReturn->sub_total_purchase_price = $salesReturn->items
                    ->sum(fn($item) => $item->purchase_price * $item->quantity);


                // Persediaan debit
                // Hpp credit
                $journal->generateJournal(
                    data: $salesReturn,
                    details: $salesReturn->items,
                    module: 'sales-return',
                    action: 'hpp',
                    columnPaymentMethod: 'payment_method',
                    columnContact: 'customer',
                    columnDescription: 'description',
                    columnNominalDebit: 'sub_total_purchase_price',
                    columnNominalCredit: 'sub_total_purchase_price'
                );

                $salesReturn->sub_total_service = $salesReturn->items
                    ->sum(fn($item) => $item->service);
                // Biaya Service debit
                // Kas credit
                if ($salesReturn->sub_total_service > 0) {
                    $journal->generateJournal(
                        data: $salesReturn,
                        details: $salesReturn->items,
                        module: 'service',
                        action: 'service',
                        columnPaymentMethod: 'payment_method',
                        columnContact: 'customer',
                        columnDescription: 'description',
                        columnNominalDebit: 'sub_total_service',
                        columnNominalCredit: 'sub_total_service'
                    );
                }
                break;
            case 'delete':
                $journal->destroyJournal($salesReturn);
                break;
            default:
                break;
        }
    }
}
