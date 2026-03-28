<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtkRequestItems extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'atk_request_id',
        'item_id',
        'item_detail_id',
        'item_code',
        'item_name',
        'quantity_requested',
        'quantity_approved',
        'quantity_fulfilled',
        'price',
        'unit',
        'unit_id',
        'sub_total',
        'notes',
    ];

    public function atkRequest()
    {
        return $this->belongsTo(AtkRequest::class);
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
