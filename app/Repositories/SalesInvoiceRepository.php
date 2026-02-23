<?php

namespace App\Repositories;

use App\Models\AdvanceSaleItems;
use Illuminate\Validation\ValidationException;
use App\Traits\Validate;
use App\Models\Items;

class SalesInvoiceRepository
{
    use Validate;
    public function createOrUpdateItems($salesInvoice, $data)
    {
        // Handle Create Items
        $ids = [];
        foreach ($data['items'] as $item) {
            $this->ensureSellingPriceNotBelowCost($item);
            $relatedItem = $this->existsWhereId(new Items(), $item['item_id']);

            if ($item['detail_id']) {
                $detail = $salesInvoice->items()->where('id', $item['detail_id'])->firstOrFail();

                if ($detail) {
                    $detail->update(array_merge($item, [
                        'item_name' => $relatedItem->name,
                        'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                    ]));
                }

                $ids[] = $detail->id;
            } else {

                $detail = $salesInvoice->items()->create(array_merge($item, [
                    'coa_id' => 1,
                    'sales_invoice_id' => $salesInvoice->id,
                    'item_name' => $relatedItem->name,
                    'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                ]));


                $ids[] = $detail->id;
            }
        }

        $salesInvoice->items()->whereNotIn('id', $ids)->delete();


        $salesInvoice = $salesInvoice->fresh();

        if ($salesInvoice->status >= 2) {
            // Handle Payment Amount
            $this->ensureInvoicePaidIn($salesInvoice);


            if ($salesInvoice->advance_sale_id) {

                // Handle quantity advance sale items
                $salesInvoice = $this->applyAdvanceQuantity($salesInvoice);

                // Handle Status Advance Sale
                $this->refreshStatus($salesInvoice);
            }
        }
    }

    public function deleteItems($salesInvoice)
    {

        if ($salesInvoice->status >= 2) {


            if ($salesInvoice->advance_sale_id) {
                // Remove Quantity in Advance Sale
                $this->applyAdvanceQuantity($salesInvoice, 'delete');

                // Change status advance sale
                $this->refreshStatus($salesInvoice);
            }
        }

        if ($salesInvoice->items()) {
            $salesInvoice->items()->delete();
        }
    }

    protected function ensureInvoicePaidIn($salesInvoice)
    {
        $total = $salesInvoice->paid_amount + $salesInvoice->advance_amount;

        if ((float) $salesInvoice->grand_total !== $total) {
            throw ValidationException::withMessages([
                'advance_amount' => 'Jumlah pembayaran harus lunas'
            ]);
        }
    }


    protected function ensureSellingPriceNotBelowCost($item)
    {
        $salePrice = ($item['sale_price'] * $item['quantity']) - $item['service'];
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

    protected function applyAdvanceQuantity($salesInvoice, $method = 'create')
    {

        $salesInvoice->items->each(function ($item) use ($method) {
            $advanceSaleItems = AdvanceSaleItems::findOrFail($item->advance_sale_items_id);

            if (!$advanceSaleItems) {
                throw ValidationException::withMessages([
                    'advance_sale_items_id' => 'barang ' . $item['name'] . ' tidak ditemukan.',
                ]);
            }

            $siQuantity = 0;
            switch ($method) {
                case 'create':
                    $siQuantity = $item->quantity;
                    break;
                case 'delete':
                    $siQuantity = 0;
                    break;
                default:
                    $siQuantity = $item->quantity;
                    break;
            }


            $advanceSaleItems->update([
                'sales_invoice_items_quantity' => $siQuantity
            ]);

            $advanceSaleItems = $advanceSaleItems->fresh();

            if ($advanceSaleItems->sales_invoice_items_quantity > $advanceSaleItems->quantity) {
                throw ValidationException::withMessages([
                    'advance_sale_items_id' => 'barang ' . $item['name'] . ' sudah melebihi batas quantity di uang muka.',
                ]);
            }
        });

        return $salesInvoice->fresh();
    }
}
