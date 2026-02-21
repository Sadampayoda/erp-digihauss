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
                        'item_name' => $item['name'],
                        'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                    ]));
                }

                $ids[] = $detail->id;
            } else {

                $detail = $salesInvoice->items()->create(array_merge($item, [
                    'coa_id' => 1,
                    'sales_invoice_id' => $salesInvoice->id,
                    'item_name' => $item['name'],
                    'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                ]));


                $ids[] = $detail->id;
            }
        }

        $salesInvoice->items()->whereNotIn('id', $ids)->delete();

        if ($salesInvoice->status >= 2) {
            // Handle Payment Amount
            $this->ensureInvoicePaidIn($salesInvoice);
        }
    }

    protected function ensureInvoicePaidIn($salesInvoice)
    {

        if ($salesInvoice->grand_total !== $salesInvoice->paid_amount) {
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
}
