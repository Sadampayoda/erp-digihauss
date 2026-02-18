<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


trait AutoNumberTransaction
{
    public function generateTransactionNumber(
        string $model,
        string $column = 'transaction_number',
        string $prefix,
        $transactionDate = null
    ): string {
        return DB::transaction(function () use ($model, $column, $prefix, $transactionDate) {

            $carbon = $transactionDate ? Carbon::parse($transactionDate) : Carbon::now();

            $year  = $carbon->format('y'); // 25
            $month = $carbon->format('m'); // 01

            // DIG-AS-2501%
            $pattern = "DIG-{$prefix}-{$year}{$month}%";

            $lastTransaction = $model::query()
                ->where($column, 'like', $pattern)
                ->lockForUpdate()
                ->orderBy($column, 'desc')
                ->first();

            $nextNumber = 1;

            if ($lastTransaction) {
                $lastCode = $lastTransaction->{$column};

                // Ambil 3 digit terakhir
                $lastNumber = (int) substr($lastCode, -3);
                $nextNumber = $lastNumber + 1;
            }

            $sequence = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            return "DIG-{$prefix}-{$year}{$month}{$sequence}";
        });
    }
}
