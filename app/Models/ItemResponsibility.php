<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemResponsibility extends Model
{
    use HasFactory,SoftDeletes, CreatedUpdatedDeletedBy;

    protected $table = 'item_responsibilitis';
    protected $fillable = [
        'user_id',
        'item_id',
        'item_detail_id',
        'assigned_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemDetail()
    {
        return $this->belongsTo(ItemDetail::class);
    }

}
