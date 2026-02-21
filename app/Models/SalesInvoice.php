<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'advance_sale_id',
        'customer',
        'sales',
        'payment_method',
        'status',
        'description',
        'sub_total',
        'discount',
        'grand_total',
        'service',
        'paid_amount',
        'remaining_amount',
        'coa_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function items()
    {
        return $this->hasMany(SalesInvoiceItems::class, 'sales_invoice_id','id');
    }
}
