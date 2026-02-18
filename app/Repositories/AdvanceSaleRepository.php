<?php

namespace App\Repositories;

use Illuminate\Validation\ValidationException;
use App\Traits\Validate;
use App\Models\Items;

class AdvanceSaleRepository
{
    use Validate;
    public function createItems($advanceSale, $data)
    {
        // Handle Payment Amount
        $this->ensureInvoicePaidIn($advanceSale);

        // Handle Create Items
        foreach ($data['items'] as $item) {
            $this->ensureSellingPriceNotBelowCost($item);
            $relatedItem = $this->existsWhereId(new Items(),$item['item_id']);

            $advanceSale->items()->create(array_merge($item,[
                'item_name' => $item['name'],
                'item_code' => $relatedItem->code,
                'coa' => 'Barang',
            ]));
        }

    }

    protected function ensureInvoicePaidIn($advanceSale)
    {

        if ($advanceSale->advance_amount <= 0) {
            throw ValidationException::withMessages([
                'advance_amount' => 'Jumlah pembayaran harus lebih dari 0.'
            ]);
        }

        if($advanceSale->advance_amount > $advanceSale->remaining_amount) {
            throw ValidationException::withMessages([
                'advance_amount' => 'Jumlah bayar tidak boleh lebih dari total pembayaran.',
            ]);
        }
    }


    protected function ensureSellingPriceNotBelowCost($item)
    {
        $salePrice = ($item['sale_price'] * $item['quantity']) - $item['service'];
        $purchasePrice = $item['purchase_price'] * $item['quantity'];
        if($salePrice < $purchasePrice) {
            throw ValidationException::withMessages([
                'margin' => 'Harga jual barang '.$item['name'].' tidak boleh lebih kecil dari harga beli.',
            ]);
        }
    }
}
