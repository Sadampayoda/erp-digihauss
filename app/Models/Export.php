<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'file',
        'status',
        'filters',
        'progress',
        'message',
        'error',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'progress' => 'integer',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
