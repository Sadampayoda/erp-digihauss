<?php

namespace App\Repositories;

use App\Traits\Validate;

class ItemRepositrory
{
    use Validate;

    public function getStockOnHandInItem($items)
    {
        return $items->map(function ($item) {
            $item->stock = $item->details->count();
            return $item;
        });
    }

    public function getStockOnAvailableInItem($items)
    {
        return $items->map(function ($item) {

            $details = $item->details;

            $notReady = $details->filter(function ($detail) {
                return $detail->condition && $detail->condition->ready == 0;
            })->count();

            $stockAvailable = $details->count() - $notReady;

            $item->stock_available = $stockAvailable;

            return $item;
        });
    }
}
