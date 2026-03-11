<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemDetail extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedDeletedBy;

    protected $fillable = [
        'item_id',

        // spesifikasi
        'color',
        'internal_storage',
        'network',
        'region',

        // identitas device
        'imei',
        'serial_number',

        // tipe
        'type',

        // kelengkapan
        'has_box',
        'has_cable',
        'has_adapter',

        // harga
        'purchase_price',
        'sale_price',
        'service',

        // supplier
        'distributor',

        // tanggal
        'purchase_date',
        'sale_date',

        // audit
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'has_box' => 'boolean',
        'has_cable' => 'boolean',
        'has_adapter' => 'boolean',
        'purchase_date' => 'date',
        'sale_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }

    public function condition()
    {
        return $this->hasOne(ItemCondition::class,'item_detail_id','id');
    }


    public function getItemCodeAttribute()
    {
        return $this->item?->code ?? null;
    }

    public function getItemNameAttribute()
    {
        return $this->item?->name ?? null;
    }
}
