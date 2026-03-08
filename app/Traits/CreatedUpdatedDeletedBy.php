<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait CreatedUpdatedDeletedBy
{
    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check() && empty($model->created_by)) {
                $model->created_by = Auth::id();
            }

            if (Auth::check() && empty($model->updated_by)) {
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check() && $model->isForceDeleting() === false) {
                $model->deleted_by = Auth::id();
                $model->save();
            }
        });
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }


    public function getCreatedByNameAttribute()
    {
        return $this->creator?->name;
    }

    public function getUpdatedByNameAttribute()
    {
        return $this->updater?->name;
    }

    public function getDeletedByNameAttribute()
    {
        return $this->deleter?->name;
    }
}
