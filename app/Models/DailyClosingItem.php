<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyClosingItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'transaction_detail_id',
        'closing_id',

        'item_id',
        'item_detail_id',

        'sale_price',
        'service',
        'purchase_price',

        'notes',
    ];

    public function itemDetail()
    {
        return $this->hasOne(ItemDetail::class,'id','item_detail_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class,'id','item_id');
    }

    public function salesInvoice()
    {
        return $this->hasOne(SalesInvoice::class,'id','transaction_id');
    }
}
