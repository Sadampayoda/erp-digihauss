<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturnItems extends Model
{
    protected $fillable = [
        'sales_return_id',
        'item_id',
        'sales_invoice_items_id',
        'coa_id',

        'item_code',
        'item_name',
        'image',

        'quantity',
        'sale_price',
        'purchase_price',
        'sub_total',
        'service',
        'margin',

        'notes',
    ];
}
