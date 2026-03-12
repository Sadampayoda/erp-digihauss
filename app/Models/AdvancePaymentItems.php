<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvancePaymentItems extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'advance_payment_id',
        'coa',
        'item_id',
        'image',
        'item_code',
        'item_name',
        'quantity',
        'sale_price',
        'purchase_price',
        'service',
        'notes',
        'receipt_invoice_items_quantity',
        'item_detail_id',
        'serial_number',
    ];

    public function advancePayment()
    {
        return $this->belongsTo(AdvancePayment::class, 'advance_payment_id');
    }

    public function item()
    {
        return $this->hasOne(Items::class,'id','item_id');
    }

    public function getOutstandingQuantityAttribute()
    {
        return $this->quantity - $this->receipt_invoice_items_quantity;
    }

    protected $appends = ['outstanding_quantity'];
}
