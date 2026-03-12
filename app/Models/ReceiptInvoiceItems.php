<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptInvoiceItems extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'receipt_invoice_id',
        'item_id',
        'advance_payment_items_id',
        'purchase_return_items_quantity',
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
        'item_detail_id',
        'serial_number'
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'receipt_invoice_id');
    }

    public function item()
    {
        return $this->hasOne(Items::class, 'id', 'item_id');
    }
}
