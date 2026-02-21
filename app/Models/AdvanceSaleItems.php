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
    ];

    public function advanceSale()
    {
        return $this->belongsTo(AdvanceSale::class, 'advance_sale_id');
    }

    public function item()
    {
        return $this->hasOne(Items::class,'id','item_id');
    }
}
