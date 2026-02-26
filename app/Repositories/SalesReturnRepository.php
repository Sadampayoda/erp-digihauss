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

    public function deleteItems($salesInvoice)
    {
        $this->settingJournal($salesInvoice, 'delete');
        if ($salesInvoice->status >= 2) {


            if ($salesInvoice->advance_sale_id) {
                // Remove Quantity
                $this->applySalesInvoiceQuantity($salesInvoice, 'delete');

                // Change status advance sale
                $this->refreshStatus($salesInvoice);
            }
        }

        if ($salesInvoice->items()) {
            $salesInvoice->items()->delete();
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

    protected function refreshStatus($salesInvoice)
    {
        $advanceSale = $salesInvoice->AdvanceSale;

        if (!$advanceSale) {
            throw ValidationException::withMessages([
                'advance_sale_id' => 'Transaksi Uang Muka tidak ditemukan.',
            ]);
        }

        // Check Quantity and items
        $items = $advanceSale->items;

        if ($items->every(fn($i) => $i->sales_invoice_items_quantity == 0)) {
            $status = 2;
        } elseif ($items->every(fn($i) => $i->sales_invoice_items_quantity == $i->quantity)) {
            $status = 4;
        } else {
            $status = 3;
        }

        $advanceSale->update([
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


    public function settingJournal($salesInvoice, $method = 'create')
    {
        $journal = app(JournalRepository::class);

        switch ($method) {
            case 'create':
                // Piutang Usaha debit
                // Pendapatan credit
                $journal->generateJournal(
                    data: $salesInvoice,
                    details: $salesInvoice->items,
                    module: 'sales-invoice',
                    action: 'revenue',
                    columnPaymentMethod: 'payment_method',
                    columnContact: 'customer',
                    columnDescription: 'description',
                    columnNominalDebit: 'sub_total',
                    columnNominalCredit: 'sub_total'
                );

                // Kas / Bank debit
                // Piutang credit
                if ($salesInvoice->paid_amount > 0) {
                    $journal->generateJournal(
                        data: $salesInvoice,
                        details: $salesInvoice->items,
                        module: 'sales-invoice',
                        action: 'payment',
                        columnPaymentMethod: 'payment_method',
                        columnContact: 'customer',
                        columnDescription: 'description',
                        columnNominalDebit: 'paid_amount',
                        columnNominalCredit: 'paid_amount'
                    );
                }

                $salesInvoice->sub_total_purchase_price = $salesInvoice->items
                    ->sum(fn($item) => $item->purchase_price * $item->quantity);


                // Hpp debit
                // Persediaan credit
                $journal->generateJournal(
                    data: $salesInvoice,
                    details: $salesInvoice->items,
                    module: 'sales-invoice',
                    action: 'hpp',
                    columnPaymentMethod: 'payment_method',
                    columnContact: 'customer',
                    columnDescription: 'description',
                    columnNominalDebit: 'sub_total_purchase_price',
                    columnNominalCredit: 'sub_total_purchase_price'
                );

                $salesInvoice->sub_total_service = $salesInvoice->items
                    ->sum(fn($item) => $item->service);
                // Biaya Service debit
                // Kas credit
                if ($salesInvoice->sub_total_service > 0) {
                    $journal->generateJournal(
                        data: $salesInvoice,
                        details: $salesInvoice->items,
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
                $journal->destroyJournal($salesInvoice);
                break;
            default:
                break;
        }
    }
}
