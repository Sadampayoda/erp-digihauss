<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDailyClosingRequest;
use App\Models\DailyClosing;
use App\Models\Journal;
use App\Repositories\DailyClosingRepository;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyClosingController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage;
    protected $model, $dailyClosingRepo;
    public function __construct(DailyClosingRepository $dailyClosingRepository)
    {
        $this->model = new DailyClosing();
        $this->dailyClosingRepo = $dailyClosingRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.closings.days.index', [
            'closing_days' => $this->model->all(),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setupColumnTransaction = [
            'transaction_number' => ['label' => 'No. Transaksi'],
            'name' => ['label' => 'Nama Produk'],
            'serial_number' => ['label' => 'Seri'],
            'sale_price_base' => ['label' => 'Harga Jual Awal', 'edit' => true, 'type' => 'number'],
            'purchase_price_base' => ['label' => 'Harga Beli Awal', 'type' => 'number'],
            'service_base' => ['label' => 'Service Awal', 'type' => 'number'],
            'sale_price' => ['label' => 'Harga Jual', 'edit' => true, 'type' => 'number'],
            'purchase_price' => ['label' => 'Harga Beli', 'type' => 'number'],
            'service' => ['label' => 'Service', 'type' => 'number'],
        ];

        $setupColumn = [
            'transaction_date' => ['label' => 'Tanggal Terbit'],
            'coa' => ['label' => 'Akun Coa'],
            'coa_name' => ['label' => 'Coa Nama'],
            'description' => ['label' => 'Uraian'],
            'debit' => ['label' => 'Debit', 'type' => 'number'],
            'credit' => ['label' => 'Kredit', 'type' => 'number'],
        ];
        return view('dashboard.closings.days.create', [
            // 'data' => DailyClosing::
            'setupColumnTransaction' => $setupColumnTransaction,
            'setupColumn' => $setupColumn,
            'journal' => Journal::with('details')->limit(1)->orderBy('journal_date', 'desc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyClosing $dailyClosing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $setupColumnTransaction = [
            'transaction_number' => ['label' => 'No. Transaksi'],
            'name' => ['label' => 'Nama Produk'],
            'serial_number' => ['label' => 'Seri'],
            'sale_price_base' => ['label' => 'Harga Jual Awal', 'edit' => true, 'type' => 'number'],
            'purchase_price_base' => ['label' => 'Harga Beli Awal', 'type' => 'number'],
            'service_base' => ['label' => 'Service Awal', 'type' => 'number'],
            'sale_price' => ['label' => 'Harga Jual', 'edit' => true, 'type' => 'number'],
            'purchase_price' => ['label' => 'Harga Beli', 'type' => 'number'],
            'service' => ['label' => 'Service', 'type' => 'number'],
        ];

        return view('dashboard.closings.days.create', [
            'closing' => $this->existsWhereId($this->model,$id),
            'setupColumnTransaction' => $setupColumnTransaction,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateDailyClosingRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $this->dailyClosingRepo->syncClosingDay($request->validated());

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Syncronisasi Data Closing Day');
        } catch (QueryException $e) {
            DB::rollBack();

            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyClosing $dailyClosing)
    {
        //
    }
}
