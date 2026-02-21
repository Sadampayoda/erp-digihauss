<?php

if (! function_exists('transactionStatus')) {
    function transactionStatus($type)
    {
        switch($type) {
            case 'transaction':
                $status = [
                    0 => 'Draft',
                    1 => 'Appoved',
                    2 => 'Pending',
                    3 => 'Inprogress',
                    4 => 'Completed',
                    5 => 'Close',
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
            1 => ['label' => 'Approved',   'class' => 'bg-emerald-100 text-emerald-700'],
            2 => ['label' => 'Pending',    'class' => 'bg-yellow-100 text-yellow-700'],
            3 => ['label' => 'In Progress','class' => 'bg-blue-100 text-blue-700'],
            4 => ['label' => 'Completed',  'class' => 'bg-green-100 text-green-700'],
            5 => ['label' => 'Close',      'class' => 'bg-stone-200 text-stone-700'],
            default => ['label' => '-',    'class' => 'bg-slate-100 text-slate-500'],
        };
    }
}
