<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyClosingItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'transaction_detail_id',
        'closing_id',

        'item_id',
        'item_detail_id',

        'sale_price',
        'service',
        'purchase_price',

        'notes',
    ];
}
