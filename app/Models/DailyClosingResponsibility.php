<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyClosingResponsibility extends Model
{
    protected $fillable = [
        'item_responsibility_id',
        'closing_id',

        'item_id',
        'item_detail_id',

        'sale_price',
        'service',
        'purchase_price',

        'notes',
    ];
}
