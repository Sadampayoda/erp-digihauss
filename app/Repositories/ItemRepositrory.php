<?php

namespace App\Repositories;

use App\Models\ItemDetail;
use App\Traits\Validate;
use Carbon\Carbon;

class ItemRepositrory
{
    use Validate;

    public function getStockOnHandInItem($items)
    {
        return $items->map(function ($item) {
            $item->stock = $item->details->where('status', 1)->count();
            return $item;
        });
    }

    public function getStockOnAvailableInItem($items)
    {
        return $items->map(function ($item) {

            $available = $item->details->filter(function ($detail) {

                // hanya ambil item yang statusnya In Stock
                if ($detail->status != 1) {
                    return false;
                }

                // jika kondisi barang tidak ready
                if ($detail->condition && $detail->condition->ready === 0) {
                    return false;
                }

                // jika sudah dipakai di transaksi
                if ($detail->advanceSale || $detail->salesInvoice) {
                    return false;
                }

                return true;
            });

            $item->stock_available = $available->count();

            return $item;
        });
    }

    public function canDeleteItemDetail($detail)
    {
        if($detail->status >= 2) {

        }
    }


    public function updateItemDetail($transaction, $method = 'create', $module = 'sales')
    {
        $transaction = $transaction->fresh();

        foreach ($transaction->items as $item) {

            $itemDetail = ItemDetail::find($item->item_detail_id);

            if (!$itemDetail) {
                continue;
            }

            $data = [];

            if ($module == 'purchase') {

                if ($method == 'create') {
                    // Purchase Invoice
                    $data['purchase_price'] = $item->purchase_price;
                    $data['purchase_date'] = Carbon::now();
                    $data['status'] = 1; // in stock

                    $data['service'] = $item->service ?? 0;
                } else {
                    // Purchase Return
                    $data['purchase_date'] = null;
                    $data['status'] = 0; // pending
                }
            }
            if ($module == 'sales') {

                if ($method == 'create') {
                    // Sales Invoice
                    $data['sale_date'] = Carbon::now();
                    $data['status'] = 3; // sold
                } else {
                    // Sales Return
                    $data['sale_date'] = null;
                    $data['status'] = 1; // back to stock
                }
            }

            $itemDetail->update($data);
        }
    }
}
