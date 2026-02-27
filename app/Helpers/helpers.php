<?php

if (! function_exists('transactionStatus')) {
    function transactionStatus($type)
    {
        switch($type) {
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
            default:
                $status = [];
        }

        return $status;
    }
}


if (! function_exists('transactionStatusBadge')) {
    function transactionStatusBadge($value)
    {

    return match ((int) $value) {
            0 => ['label' => 'Draft',      'class' => 'bg-slate-100 text-slate-700'],
            1 => ['label' => 'Need Appoved',      'class' => 'bg-slate-100 text-slate-700'],
            2 => ['label' => 'Approved',   'class' => 'bg-emerald-100 text-emerald-700'],
            3 => ['label' => 'In Progress','class' => 'bg-blue-100 text-blue-700'],
            4 => ['label' => 'Completed',  'class' => 'bg-green-100 text-green-700'],
            5 => ['label' => 'Pending',    'class' => 'bg-yellow-100 text-yellow-700'],
            6 => ['label' => 'Close',      'class' => 'bg-red-200 text-red-700'],
            default => ['label' => '-',    'class' => 'bg-slate-100 text-slate-500'],
        };
    }
}
