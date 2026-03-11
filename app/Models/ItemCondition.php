<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemCondition extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedDeletedBy;


    protected $fillable = [
        'item_detail_id',

        'battery_health',

        'body_condition',
        'lcd_condition',
        'face_id_condition',
        'battery_condition',

        'front_camera_condition',
        'rear_camera_condition',

        'speaker_top_condition',
        'speaker_bottom_condition',

        'housing_condition',

        'ready',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'battery_health' => 'integer',
        'ready' => 'boolean',
    ];

    public function detail()
    {
        return $this->belongsTo(ItemDetail::class, 'item_detail_id');
    }

    public function getItemCodeAttribute()
    {
        return $this->detail?->item?->code ?? null;
    }

    public function getItemNameAttribute()
    {
        return $this->detail?->item?->name ?? null;
    }


    public function getSerialNumberAttribute()
    {
        return $this->detail?->serial_number ?? null;
    }

    public function getImeiAttribute()
    {
        return $this->detail?->imai ?? null;
    }

    public function getColorAttribute()
    {
        return $this->detail?->color ?? null;
    }
}
