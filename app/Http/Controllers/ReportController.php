<?php

namespace App\Http\Controllers;

use App\Repositories\ReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $reportRepo;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepo = $reportRepository;
    }

    public function index(Request $request)
    {
        $setupReport = $this->reportRepo->setupReport();
        $type = $request->type;

        if (!$setupReport[$type]) {
            abort(404);
        }

        $startDate = Carbon::now()->startOfMonth()->toDateString();
        $endDate   = Carbon::now()->endOfMonth()->toDateString();

        return view('dashboard.reports.index', [
            'setupReport' => $setupReport[$type],
            'startDate' => $startDate,
            'endDate' => $endDate,
            'report_type' => $type,
        ]);
    }

    public function exportExcel(Request $request)
    {
        try {
            DB::beginTransaction();

            $type = $request->report_type;
            $setupReport = $this->reportRepo->setupReport()[$type] ?? null;

            if (!$setupReport) {
                abort(404);
            }

            // create export
            $export = \App\Models\Export::create([
                'type' => $type,
                'status' => 'pending',
                'filters' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            $sql = ($setupReport['sql_repo'])->sql();

            // dispatch job
            $setupReport['file_job']::dispatch(
                $sql,
                $type,
                $request->all(),
                $export->id,
                $setupReport['title']
            );

            DB::commit();

            return redirect()->route('export.loading', $export->id);
        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal memulai export: ' . $e->getMessage());
        }
    }
}
