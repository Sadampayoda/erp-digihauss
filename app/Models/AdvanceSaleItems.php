<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceSaleItems extends Model
{
    use HasFactory;
    protected $table = 'advance_sale_items';

    protected $fillable = [
        'advance_sale_id',
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
        'sales_invoice_items_quantity'
    ];

    public function advanceSale()
    {
        return $this->belongsTo(AdvanceSale::class, 'advance_sale_id');
    }

    public function item()
    {
        return $this->hasOne(Items::class,'id','item_id');
    }

    public function getOutstandingQuantityAttribute()
    {
        return $this->quantity - $this->sales_invoice_items_quantity;
    }

    protected $appends = ['outstanding_quantity'];
}
