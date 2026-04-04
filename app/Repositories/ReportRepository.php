<?php

namespace App\Repositories;

use App\Jobs\InvoiceExportExcelJob;

class ReportRepository
{

    public function filterReport($data, $request)
    {
        $filters = $this->setupReport()[$request->report_type]['filter'];

        foreach ($filters as $name => $filter) {
            switch ($filter['input_type']) {
                case 'date_range':
                    $data->when($request->{$name . '_start'}, function ($query) use ($name, $request) {
                        $query->where($name, '>=', $request->{$name . '_start'});
                    });

                    $data->when($request->{$name . '_end'}, function ($query) use ($name, $request) {
                        $query->where($name, '<=', $request->{$name . '_end'});
                    });
                    break;
                case 'select':
                    $data->when($request->$name, function ($query) use ($name, $request) {
                        $query->where($name, $request->$name);
                    });
                    break;
                case 'status':
                    $data->when($request->$name, function ($query) use ($name, $request) {
                        $query->where($name, $request->$name);
                    });
                    break;
                default:
                    $data->when($request->$name, function ($query) use ($name, $request) {
                        $query->where($name, 'ilike', '%' . $request->$name . '%');
                    });
                    break;
            }
        }

        return $data;
    }

    public function setupReport()
    {
        return [
            'invoice' => [
                'sql_repo' => new InvoiceReportRepository(),
                'file_job' => InvoiceExportExcelJob::class,
                'excel' => true,
                'pdf' => true,
                'title' => 'Laporan Penjualan',
                'description' => 'Laporan untuk data penjualan',
                'filter' => [
                    'transaction_number' => [
                        'input_type' => 'default',
                        'type' => 'text',
                        'label' => 'No Transaksi',
                        'placeholder' => 'No. Transaksi',
                    ],
                    'transaction_date' => [
                        'input_type' => 'date_range',
                        'label' => 'Tanggal Transaksi',
                        'placeholder' => 'Tanggal Transaksi',
                    ],
                    'customer' => [
                        'input_type' => 'select',
                        'label' => 'Pelanggan',
                        'placeholder' => 'Pelanggan',
                        'route' => route('contacts.index'),
                        'params' => ['type' => 0],
                    ],
                    'status' => [
                        'input_type' => 'status',
                        'label' => 'Status',
                        'allowed' => [0, 1, 2, 3, 4, 5, 6]
                    ]
                ]
            ]
        ];
    }


    public function setupViewColumn()
    {
        return [
            'invoice' => (new InvoiceReportRepository())->viewColumn(),
        ];
    }
}
