<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReturnItems extends Model
{
    use HasFactory, SoftDeletes;
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

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class, 'sales_return_id');
    }

    public function salesInvoiceItem()
    {
        return $this->hasOne(SalesInvoiceItems::class,'id','sales_invoice_items_id');
    }

    public function getSiQuantityAttribute()
    {
        $salesInvoiceItem = $this->salesInvoiceItem;
        if($salesInvoiceItem) {
            return $salesInvoiceItem->quantity - $salesInvoiceItem->sales_return_items_quantity;
        }

        return 0;
    }

    public function item()
    {
        return $this->hasOne(Items::class, 'id', 'item_id');
    }

    protected $appends = ['si_quantity'];
}
