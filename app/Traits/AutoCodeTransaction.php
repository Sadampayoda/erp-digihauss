<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

trait AutoCodeTransaction
{
    public function generateCodeTransaction($model, $prefix, $column)
    {
        return DB::transaction(function () use ($model, $prefix, $column) {

            $dateFormat = Carbon::now()->format('Ymd');
            $pattern = "{$prefix}-{$dateFormat}-%";

            $lastTransaction = $model
                ->where($column, 'like', $pattern)
                ->lockForUpdate()
                ->orderBy($column, 'desc')
                ->first();

            if ($lastTransaction) {

                $lastCode = $lastTransaction->$column;
                $lastNumber = (int) substr($lastCode, -4);

                $nextNumber = $lastNumber + 1;
            } else {

                $nextNumber = 1;
            }

            $sequence = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            return "{$prefix}-{$dateFormat}-{$sequence}";
        });
    }
}
