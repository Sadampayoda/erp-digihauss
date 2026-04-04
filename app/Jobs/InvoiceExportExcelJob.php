<?php

namespace App\Jobs;

use App\Repositories\ReportRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class InvoiceExportExcelJob implements ShouldQueue
{
    use Queueable;

    protected $sql;
    protected $filters;

    protected $type;

    protected $exportId;

    protected $title;

    /**
     * Create a new job instance.
     */
    public function __construct($sql, $type, $filters, $exportId, $title)
    {
        $this->sql = $sql;
        $this->type = $type;
        $this->filters = $filters;
        $this->exportId = $exportId;
        $this->title = $title;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $reportRepo = app(ReportRepository::class);
            progressExport($this->exportId, 5, 'Menyiapkan query...', 'processing');

            $query = DB::table(DB::raw("({$this->sql}) as base"));

            progressExport($this->exportId, 15, 'Menerapkan filter...');

            $query = $reportRepo->filterReport($query, (object) $this->filters);

            progressExport($this->exportId, 30, 'Mengambil struktur kolom...');

            $columns = $reportRepo->setupViewColumn()[$this->type];

            progressExport($this->exportId, 50, 'Memproses data...');
            Storage::makeDirectory('exports');


            $fileName = 'report_' .$this->title.'-'.time() . '.xlsx';
            $relativePath = "exports/{$fileName}";
            $path = Storage::path($relativePath);

            $total = $query->count();
            $processed = 0;

            (new FastExcel($this->generate($query)))
                ->export($path, function ($row) use (&$processed, $total, $columns) {

                    $processed++;

                    if ($processed % 100 == 0) {
                        $percent = 50 + intval(($processed / $total) * 40);

                        progressExport(
                            $this->exportId,
                            $percent,
                            "Memproses {$processed} dari {$total} data..."
                        );
                    }

                    $result = [];

                    foreach ($columns as $field => $config) {
                        $result[$config['label']] = $row->$field ?? null;
                    }

                    return $result;
                });

            progressExport($this->exportId, 100, 'Export selesai', 'done');

            \App\Models\Export::where('id', $this->exportId)->update([
                'file' => $fileName
            ]);
        } catch (\Throwable $e) {

            progressExport($this->exportId, 0, 'Export gagal', 'failed');

            throw $e;
        }
    }

    public function generate($query)
    {
        foreach ($query->cursor() as $row) {
            yield $row;
        }
    }
}
