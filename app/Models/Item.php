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

    public function getBrandNameAttribute()
    {
        return $this->brandRelation ? $this->brandRelation?->name : null;
    }
}
