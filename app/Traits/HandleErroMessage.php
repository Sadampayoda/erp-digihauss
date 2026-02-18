<?php

namespace App\Traits;

use Illuminate\Database\QueryException;

trait HandleErroMessage
{
    public function handleDatabaseError(QueryException $e): string
{
    $sqlState = $e->errorInfo[0] ?? null;

    return match ($sqlState) {
        '23505' => 'Data sudah digunakan.',
        '23503' => 'Data masih terhubung dengan data lain.',
        '23502' => 'Field wajib belum diisi.',
        default  => 'Terjadi kesalahan sistem.'
    };
}
}
