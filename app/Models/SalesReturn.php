<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    protected $fillable = [
        'transaction_number',
        'transaction_date',

        'sales_invoice_id',
        'customer',
        'sales',

        'sub_total',
        'service',
        'discount',
        'grand_total',

        'paid_amount',
        'remaining_amount',

        'payment_method',
        'coa_id',

        'status',
        'description',

        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
