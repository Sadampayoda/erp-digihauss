<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory,SoftDeletes, CreatedUpdatedDeletedBy;

    protected $table = 'item';

    protected $fillable = [
        'code',
        'name',
        'brand',
        'model',
        'stock_on_hand',
        'stock_available',
        'image',
        'images',
        'created_by',
        'updated_by',
        'deleted_by',

        'unit_id',
        'type'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function details()
    {
        return $this->hasMany(ItemDetail::class,'item_id','id');
    }

    public function brandRelation()
    {
        return $this->belongsTo(Brand::class,'brand');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id');
    }

    public function getBrandNameAttribute()
    {
        return $this->brandRelation ? $this->brandRelation?->name : null;
    }

    public function getUnitNameAttribute()
    {
        return $this->unit?->name ?? '';
    }
}
