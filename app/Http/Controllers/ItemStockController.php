<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Items;
use App\Repositories\ItemRepositrory;
use Illuminate\Http\Request;

class ItemStockController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $itemRepo = app(ItemRepositrory::class);

        $items = Item::with('details')->get();
        $items = $itemRepo->getStockOnHandInItem($items);
        $items = $itemRepo->getStockOnAvailableInItem($items);


        return view('dashboard.items.stock.index',[
            'items' => $items,
        ]);
    }
}
