<?php

if (! function_exists('transactionStatus')) {
    function transactionStatus($type, $key = null)
    {
        switch ($type) {
            case 'transaction':
                $status = [
                    0 => 'Draft',
                    1 => 'Need Appoved',
                    2 => 'Appoved',
                    3 => 'Inprogress',
                    4 => 'Completed',
                    5 => 'Pending',
                    6 => 'Close',
                ];
                break;
            case 'item_details':
                $status = [
                    0 => 'Pending Receipt',   // barang dibuat tapi belum masuk receipt
                    1 => 'In Stock',          // barang sudah masuk dan siap dijual
                    2 => 'In Progress',
                    3 => 'Sold',              // barang sudah terjual
                    4 => 'Service',           // barang sedang diservice
                    5 => 'Returned',          // barang return dari customer
                    6 => 'Broken',            // barang rusak
                ];
                break;
            default:
                $status = [];
        }

        if (!isset($statuses[$type])) {
            return null;
        }

        if ($key !== null) {
            return $statuses[$type][$key] ?? null;
        }

        return $statuses[$type];
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
        $settings = [
            'closing_day_time' => '23:00',
            'closing_months_time' => '23:59',
            'closing_year_time' => '23:00', 
        ];

        return $settings[$key] ?? $default;
    }
}
