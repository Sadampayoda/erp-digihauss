<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoiceItems extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'sales_invoice_id',
        'item_id',
        'advance_sale_items_id',
        'sales_return_items_quantity',
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
        'serial_number',
    ];
    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
