<?php

namespace App\Repositories;

use App\Models\AdvanceSaleItems;
use Illuminate\Validation\ValidationException;
use App\Traits\Validate;
use App\Models\Items;

class AdvanceSaleRepository
{
    use Validate;
    public function createOrUpdateItems($advanceSale, $data)
    {
        // Handle Payment Amount
        $this->ensureInvoicePaidIn($advanceSale);

        // Handle Create Items
        $ids = [];
        foreach ($data['items'] as $item) {
            $this->ensureSellingPriceNotBelowCost($item);
            $relatedItem = $this->existsWhereId(new Items(), $item['item_id']);

            if ($item['detail_id']) {
                $detail = $advanceSale->items()->where('id', $item['detail_id'])->firstOrFail();

                if ($detail) {
                    $detail->update(array_merge($item, [
                        'item_name' => $item['name'],
                        'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                    ]));
                }

                $ids[] = $detail->id;
            } else {

                $dataCreate = [
                    'advance_sale_id' => $advanceSale->id,
                    'item_id'         => (int) $item['item_id'],
                    'item_name'       => $item['name'],
                    'item_code'       => $relatedItem->code ?? $relatedItem->item_code,
                    'quantity'        => (int) $item['quantity'],
                    'sale_price'      => (float) $item['sale_price'],
                    'purchase_price'  => (float) $item['purchase_price'],
                    'service'         => (float) ($item['service'] ?? 0),
                    'coa'             => 1,
                ];
                $detail = AdvanceSaleItems::create($dataCreate);


                $ids[] = $detail->id;
            }
        }

        $advanceSale->items()->whereNotIn('id', $ids)->delete();
    }

    protected function ensureInvoicePaidIn($advanceSale)
    {

        if ($advanceSale->advance_amount <= 0) {
            throw ValidationException::withMessages([
                'advance_amount' => 'Jumlah pembayaran harus lebih dari 0.'
            ]);
        }

        if ($advanceSale->advance_amount > $advanceSale->remaining_amount) {
            throw ValidationException::withMessages([
                'advance_amount' => 'Jumlah bayar tidak boleh lebih dari total pembayaran.',
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
