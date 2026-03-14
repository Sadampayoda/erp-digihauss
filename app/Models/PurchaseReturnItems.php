<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturnItems extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'purchase_return_id',
        'item_id',
        'receipt_invoice_items_id',
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

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function receiptInvoiceItem()
    {
        return $this->hasOne(ReceiptInvoiceItems::class, 'id', 'receipt_invoice_items_id');
    }



    public function getRiQuantityAttribute()
    {
        $receiptInvoiceItem = $this->receiptInvoiceItem;
        if ($receiptInvoiceItem) {
            return $receiptInvoiceItem->quantity - $receiptInvoiceItem->purchase_return_items_quantity;
        }

        return 0;
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    protected $appends = ['ri_quantity'];
}
