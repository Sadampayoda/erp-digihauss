<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoiceItems extends Model
{
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
    ];
    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function item()
    {
        return $this->hasOne(Items::class, 'id', 'item_id');
    }
}
