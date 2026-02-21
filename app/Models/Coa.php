<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coa extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'parent_id',
        'level',
        'is_postable',
        'is_active',
        'description',
    ];

    public static array $type = [
        'asset' => 'Aset',
        'liability' => 'Liabilitas',
        'equity' => 'Ekuitas',
        'income' => 'Pendapatan',
        'expense' => 'Beban',
    ];


    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('code');
    }

    public function scopePostable($query)
    {
        return $query->where('is_postable', true)
            ->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function generate(?self $parent): array
    {
        return DB::transaction(function () use ($parent) {

            if (!$parent) {
                $last = self::whereNull('parent_id')
                    ->orderBy('code', 'desc')
                    ->lockForUpdate()
                    ->first();

                $nextCode = $last
                    ? ((int) $last->code + 1000)
                    : 1000;

                return [
                    'code'  => (string) $nextCode,
                    'level' => 1,
                ];
            }

            $lastChild = self::where('parent_id', $parent->id)
                ->orderBy('code', 'desc')
                ->lockForUpdate()
                ->first();

            $nextCode = $lastChild
                ? ((int) $lastChild->code + 1)
                : ((int) $parent->code + 1);

            return [
                'code'  => (string) $nextCode,
                'level' => $parent->level + 1,
            ];
        });
    }
}
