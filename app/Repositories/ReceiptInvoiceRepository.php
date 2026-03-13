<?php

namespace App\Repositories;

use App\Models\AdvancePaymentItems;
use App\Models\AdvanceSaleItems;
use App\Models\Item;
use App\Models\ItemDetail;
use Illuminate\Validation\ValidationException;
use App\Traits\Validate;
use App\Models\Items;
use Carbon\Carbon;

class ReceiptInvoiceRepository
{
    use Validate;
    public function createOrUpdateItems($receiptInvoice, $data)
    {
        // Handle Create Items
        $ids = [];
        foreach ($data['items'] as $item) {
            $this->ensureSellingPriceNotBelowCost($item);
            $relatedItem = $this->existsWhereId(new Items(), $item['item_id']);

            if ($item['detail_id']) {
                $detail = $receiptInvoice->items()->where('id', $item['detail_id'])->firstOrFail();

                if ($detail) {
                    $detail->update(array_merge($item, [
                        'item_name' => $relatedItem->name,
                        'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                    ]));
                }

                $ids[] = $detail->id;
            } else {

                $detail = $receiptInvoice->items()->create(array_merge($item, [
                    'coa_id' => 1,
                    'receipt_invoice_id' => $receiptInvoice->id,
                    'item_name' => $relatedItem->name,
                    'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                ]));


                $ids[] = $detail->id;
            }
        }

        $receiptInvoice->items()->whereNotIn('id', $ids)->delete();


        $receiptInvoice = $receiptInvoice->fresh();

        if ($receiptInvoice->status >= 2) {
            // Handle Payment Amount
            $this->ensureInvoicePaidIn($receiptInvoice);


            if ($receiptInvoice->advance_payment_id) {

                // Handle quantity advance sale items
                $receiptInvoice = $this->applyAdvanceQuantity($receiptInvoice);

                // Handle Status Advance Sale
                $this->refreshStatus($receiptInvoice);

                // Handle Update Item
                $this->updateItemDetail($receiptInvoice);
            }
        }

        $this->settingJournal($receiptInvoice);
    }

    public function deleteItems($receiptInvoice)
    {
        $this->settingJournal($receiptInvoice, 'delete');
        if ($receiptInvoice->status >= 2) {

            if ($receiptInvoice->advance_payment_id) {
                // Remove Quantity in Advance payment
                $this->applyAdvanceQuantity($receiptInvoice, 'delete');

                // Change status advance payment
                $this->refreshStatus($receiptInvoice);


                $this->updateItemDetail($receiptInvoice);
            }
        }

        if ($receiptInvoice->items()) {
            $receiptInvoice->items()->delete();
        }
    }

    protected function ensureInvoicePaidIn($receiptInvoice)
    {
        $total = $receiptInvoice->paid_amount + $receiptInvoice->advance_amount;

        if ((float) $receiptInvoice->grand_total !== $total) {
            throw ValidationException::withMessages([
                'advance_amount' => 'Jumlah pembayaran harus lunas'
            ]);
        }
    }

    protected function updateItemDetail($receiptInvoice)
    {
        $receiptInvoice = $receiptInvoice->fresh();

        foreach($receiptInvoice->items as $item)
        {
            $relatedItemDetail = ItemDetail::find($item->item_detail_id);

            if($relatedItemDetail) {
                $relatedItemDetail->update([
                    'service' => $item->service ?? 0,
                    'purchase_date' => Carbon::now(),
                ]);
            }
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

    protected function refreshStatus($receiptInvoice)
    {
        $advancePayment = $receiptInvoice->advancePayment;

        if (!$advancePayment) {
            throw ValidationException::withMessages([
                'advance_payment_id' => 'Transaksi Uang Muka tidak ditemukan.',
            ]);
        }

        // Check Quantity and items
        $items = $advancePayment->items;

        if ($items->every(fn($i) => $i->receipt_invoice_items_quantity == 0)) {
            $status = 2;
        } elseif ($items->every(fn($i) => $i->receipt_invoice_items_quantity == $i->quantity)) {
            $status = 4;
        } else {
            $status = 3;
        }

        $advancePayment->update([
            'status' => $status,
        ]);
    }

    protected function applyAdvanceQuantity($receiptInvoice, $method = 'create')
    {

        $receiptInvoice->items->each(function ($item) use ($method) {
            $advancePaymentItems = AdvancePaymentItems::findOrFail($item->advance_payment_items_id);

            if (!$advancePaymentItems) {
                throw ValidationException::withMessages([
                    'advance_payment_items_id' => 'barang ' . $item['name'] . ' tidak ditemukan.',
                ]);
            }

            $riQuantity = 0;
            switch ($method) {
                case 'create':
                    $riQuantity = $item->quantity;
                    break;
                case 'delete':
                    $riQuantity = 0;
                    break;
                default:
                    $riQuantity = $item->quantity;
                    break;
            }


            $advancePaymentItems->update([
                'receipt_invoice_items_quantity' => $riQuantity
            ]);

            $advancePaymentItems = $advancePaymentItems->fresh();

            if ($advancePaymentItems->receipt_invoice_items_quantity > $advancePaymentItems->quantity) {
                throw ValidationException::withMessages([
                    'advance_payment_items_id' => 'barang ' . $item['name'] . ' sudah melebihi batas quantity di uang muka.',
                ]);
            }
        });

        return $receiptInvoice->fresh();
    }


    public function settingJournal($receiptInvoice, $method = 'create')
    {
        $journal = app(JournalRepository::class);

        switch ($method) {
            case 'create':
                // Persediaan debit
                // Hutang Usaha credit
                $journal->generateJournal(
                    data: $receiptInvoice,
                    details: $receiptInvoice->items,
                    module: 'receipt-invoice',
                    action: 'purchase',
                    columnPaymentMethod: 'payment_method',
                    columnContact: 'vendor',
                    columnDescription: 'description',
                    columnNominalDebit: 'grand_total',
                    columnNominalCredit: 'grand_total'
                );

                // Hutang Usaha debit
                // Kas / Bank credit
                if ($receiptInvoice->paid_amount > 0) {
                    $journal->generateJournal(
                        data: $receiptInvoice,
                        details: $receiptInvoice->items,
                        module: 'receipt-invoice',
                        action: 'vendor_payment',
                        columnPaymentMethod: 'payment_method',
                        columnContact: 'vendor',
                        columnDescription: 'description',
                        columnNominalDebit: 'paid_amount',
                        columnNominalCredit: 'paid_amount'
                    );
                }

                $receiptInvoice->sub_total_purchase_price = $receiptInvoice->items
                    ->sum(fn($item) => $item->purchase_price * $item->quantity);


                // Biaya Service debit
                // Kas credit
                $receiptInvoice->sub_total_service = $receiptInvoice->items
                    ->sum(fn($item) => $item->service);

                if ($receiptInvoice->sub_total_service > 0) {

                    $journal->generateJournal(
                        data: $receiptInvoice,
                        details: $receiptInvoice->items,
                        module: 'receipt-invoice',
                        action: 'service',
                        columnPaymentMethod: 'payment_method',
                        columnContact: 'vendor',
                        columnDescription: 'description',
                        columnNominalDebit: 'sub_total_service',
                        columnNominalCredit: 'sub_total_service'
                    );
                }
                break;
            case 'delete':
                $journal->destroyJournal($receiptInvoice);
                break;
            default:
                break;
        }
    }
}
