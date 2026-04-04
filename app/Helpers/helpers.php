<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

if (! function_exists('transactionStatus')) {
    function transactionStatus($type, $key = null)
    {
        switch ($type) {
            case 'transaction':
                $status = [
                    0 => 'Draft',
                    1 => 'Need Approved',
                    2 => 'Approved',
                    3 => 'In Progress',
                    4 => 'Completed',
                    5 => 'Pending',
                    6 => 'Close',
                ];
                break;

            case 'item_details':
                $status = [
                    0 => 'Pending Receipt',
                    1 => 'In Stock',
                    2 => 'In Progress',
                    3 => 'Sold',
                    4 => 'Service',
                    5 => 'Returned',
                    6 => 'Broken',
                ];
                break;
            case 'item_type':
                $status = [
                    0 => 'Barang Habis Pakai',   // ATK (Alat Tulis Kantor)
                    1 => 'Barang Dagang',        // untuk dijual
                    2 => 'Aset Tetap',           // aset perusahaan
                ];
                break;

            default:
                $status = [];
        }

        if ($key !== null) {
            return $status[$key] ?? null;
        }

        return $status;
    }
}


if (! function_exists('transactionStatusBadge')) {
    function transactionStatusBadge($value, $type = 'transaction')
    {
        switch ($type) {

            case 'item_details':
                return match ((int) $value) {
                    0 => ['label' => 'Pending Receipt', 'class' => 'bg-yellow-100 text-yellow-700'],
                    1 => ['label' => 'In Stock',        'class' => 'bg-emerald-100 text-emerald-700'],
                    2 => ['label' => 'In Progress',  'class' => 'bg-blue-100 text-blue-700'],
                    3 => ['label' => 'Sold',            'class' => 'bg-blue-100 text-blue-700'],
                    4 => ['label' => 'Service',         'class' => 'bg-purple-100 text-purple-700'],
                    5 => ['label' => 'Returned',        'class' => 'bg-orange-100 text-orange-700'],
                    6 => ['label' => 'Broken',          'class' => 'bg-red-100 text-red-700'],
                    default => ['label' => '-',         'class' => 'bg-slate-100 text-slate-500'],
                };

            case 'transaction':
            default:
                return match ((int) $value) {
                    0 => ['label' => 'Draft',        'class' => 'bg-slate-100 text-slate-700'],
                    1 => ['label' => 'Need Approved', 'class' => 'bg-yellow-100 text-yellow-700'],
                    2 => ['label' => 'Approved',     'class' => 'bg-emerald-100 text-emerald-700'],
                    3 => ['label' => 'In Progress',  'class' => 'bg-blue-100 text-blue-700'],
                    4 => ['label' => 'Completed',    'class' => 'bg-green-100 text-green-700'],
                    5 => ['label' => 'Pending',      'class' => 'bg-yellow-100 text-yellow-700'],
                    6 => ['label' => 'Close',        'class' => 'bg-red-200 text-red-700'],
                    default => ['label' => '-',      'class' => 'bg-slate-100 text-slate-500'],
                };
        }
    }
}


if (! function_exists('rupiah')) {
    function rupiah($value)
    {
        if (!is_numeric($value)) {
            return $value;
        }

        return number_format((float) $value, 0, ',', '.');
    }
}


if (! function_exists('setting')) {
    function setting($key, $default = null)
    {
        static $cache = null;
        if (!Schema::hasTable('settings')) {
            return $default;
        }


        if ($cache === null) {
            $cache = Setting::pluck('value', 'key')->toArray();
        }

        $defaults = [
            // Closing
            'closing_day_time' => '23:00',
            'closing_months_time' => '23:59',
            'closing_year_time' => '23:00',
            'closing_day_lock_after_hours' => 12,

            // GA
            'appoved_atk' => 1,
        ];

        return $cache[$key]
            ?? $defaults[$key]
            ?? $default;
    }
}


if (!function_exists('progressExport')) {
    function progressExport(
        $exportId,
        $percent,
        $message = null,
        $status = null,
        $extra = []
    ) {
        try {
            $export = \App\Models\Export::find($exportId);
            if (!$export) {
                return;
            }

            if ($percent < $export->progress) {
                $percent = $export->progress;
            }

            $data = [
                'progress' => min($percent, 100),
                'message' => $message ?? $export->message,
            ];

            if ($status) {
                $data['status'] = $status;

                // otomatis set waktu
                if ($status === 'processing' && !$export->started_at) {
                    $data['started_at'] = now();
                }

                if (in_array($status, ['done', 'failed'])) {
                    $data['finished_at'] = now();
                }
            }


            $data = array_merge($data, $extra, [
                'user_id' => auth()->id()
            ]);

            $export->update($data);
            Log::info('update export');
        } catch (\Throwable $e) {
            Log::error('progressExport error', [
                'export_id' => $exportId,
                'message' => $e->getMessage(),
                'error' => $e,
            ]);
        }
    }
}
