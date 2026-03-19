<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, CreatedUpdatedDeletedBy;
    protected $fillable = [
        'key',
        'value',
        'label',
        'type',
        'group',
        'sort_order',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];
}
