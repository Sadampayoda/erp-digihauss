<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $fillable = [
        'item_code',
        'name',
        'brand',
        'series',
        'model',
        'variant',

        'storage_gb',
        'ram_gb',
        'color',
        'condition',
        'network',
        'region',

        'status',

        'purchase_price',
        'sale_price',
        'service',
        'hpp',

        'stock_on_hand',
        'stock_available',

        'image',
        'images',
    ];
}
