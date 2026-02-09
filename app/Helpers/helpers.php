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
