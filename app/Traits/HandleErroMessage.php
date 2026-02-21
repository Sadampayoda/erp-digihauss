<?php

namespace App\Traits;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

trait HandleErroMessage
{
    public function handleDatabaseError(QueryException $e): string
{
    $sqlState = $e->errorInfo[0] ?? null;

    Log::error('Database error', [
        'sql_state' => $e->errorInfo[0] ?? null,
        'message'   => $e->getMessage(),
        'sql'       => $e->getSql(),
        'bindings'  => $e->getBindings(),
    ]);

    return match ($sqlState) {
        '23505' => 'Data sudah digunakan.',
        '23503' => 'Data masih terhubung dengan data lain.',
        '23502' => 'Field wajib belum diisi.',
        default  => config('app.debug')
            ? $e->getMessage()
            : 'Terjadi kesalahan sistem.'
    };
}
}
