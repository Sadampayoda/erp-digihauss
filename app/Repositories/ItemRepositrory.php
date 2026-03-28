<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\ItemDetail;
use App\Traits\Validate;
use Carbon\Carbon;

class ItemRepositrory
{
    use Validate;

    public function getStockSummary($items)
    {
        return $items->map(function ($item) {
            $isProduct = $item->type == 1;

            $item->stock = $isProduct
                ? $item->details->whereIn('status', [1, 2, 4, 5])->count()
                : $item->stock_on_hand;

            $item->stock_sell = $isProduct
                ? $item->details->where('status', 3)->count()
                : 0;

            $item->stock_available = $isProduct
                ? $item->details->where('status', 1)->count()
                : $item->stock_on_hand;

            return $item;
        });
    }


    public function canDeleteItemDetail($detail)
    {
        if ($detail->status >= 2) {
        }
    }


    public function updateItemDetail($transaction)
    {
        $transaction = $transaction->fresh();

        foreach ($transaction->items as $item) {
            $relatedItem = Item::findOrFail($item->item_id);
            if($relatedItem->type == 1) {
                $this->syncStatus($item->item_detail_id, $item->purchase_price, $item->sale_price, $item->service);
            }
        }
    }


    public function syncStatus($id, $purchasePrice = null, $salePrice = null, $service = null)
    {
        $itemDetail = ItemDetail::with([
            'advancePayment',
            'receiptInvoice',
            'purchaseReturn',
            'advanceSale',
            'salesInvoice',
            'salesReturn'
        ])->find($id);

        if (!$itemDetail) {
            return;
        }

        $data = [];

        $status = 0;

        $salesReturn = optional($itemDetail->salesReturn)->salesReturn;
        $salesInvoice = optional($itemDetail->salesInvoice)->salesInvoice;

        $advanceSale = optional($itemDetail->advanceSale)->advanceSale;
        $receiptInvoice = optional($itemDetail->receiptInvoice)->receiptInvoice;
        $purchaseReturn = optional($itemDetail->purchaseReturn)->purchaseReturn;

        if ($salesReturn && in_array($salesReturn->status, [2, 4])) {
            $status = 2;
        } elseif ($salesInvoice && in_array($salesInvoice->status, [2, 4])) {
            $status = 3;
            $data['sale_price'] = $salePrice ?? $itemDetail->salesInvoice?->sale_price ?? null;
            $data['sale_date'] = Carbon::now();
        } elseif ($advanceSale) {
            $status = 2;
        } elseif ($purchaseReturn && in_array($purchaseReturn->status, [2, 4])) {
            $status = 0;
            $data['purchase_date'] = null;
        } elseif ($receiptInvoice && in_array($receiptInvoice->status, [2, 4])) {
            $status = 1;
            $data['purchase_price'] = $purchasePrice;
            $data['purchase_date'] = Carbon::now();
            $data['sale_price'] = $salePrice;
            $data['service'] = $service;
        }

        $data['status'] = $status;


        $itemDetail->update($data);
    }
}
