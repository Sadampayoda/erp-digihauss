<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSalesInvoiceRequest;
use App\Models\Items;
use App\Models\SalesInvoice;
use App\Repositories\SalesInvoiceRepository;
use App\Traits\ApiResponse;
use App\Traits\AutoNumberTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesInvoiceController extends Controller
{
     use ApiResponse, Validate, HandleErroMessage, AutoNumberTransaction;
    protected $salesInvoiceRepo, $model, $setupColumn;

    public function __construct(SalesInvoiceRepository $salesInvoiceRepository)
    {
        $this->salesInvoiceRepo = $salesInvoiceRepository;
        $this->model = new SalesInvoice();
        $this->setupColumn = [
            'detail_id' => ['label' => ' ','type' => 'hidden'],
            'image' => ['label' => 'Gambar', 'type' => 'image'],
            'name' => ['label' => 'Nama Produk'],
            'variant' => ['label' => 'Varian'],
            'sale_price' => ['label' => 'Harga Jual', 'edit' => true, 'type' => 'number'],
            'purchase_price' => ['label' => 'Harga Beli', 'type' => 'number'],
            'quantity' => ['label' => 'Qty', 'edit' => true, 'type' => 'number'],
            'service' => ['label' => 'Servis', 'edit' => true, 'type' => 'number'],
            'sub_total' => ['label' => 'Sub Total', 'type' => 'number'],
            'margin' => ['label' => 'Margin', 'type' => 'number'],
            'margin_percentage' => ['label' => 'Margin (%)', 'type' => 'number'],
            'action' => ['label' => 'Action', 'delete' => true]
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.sales.sales_invoices.index', [
            'sales_invoices' => $this->model->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.sales.sales_invoices.create', [
            'items' => Items::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSalesInvoiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $salesInvoice = $this->model->create(array_merge($data, [
                'transaction_number' => $this->generateTransactionNumber(
                    model: SalesInvoice::class,
                    prefix: 'SI',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date'],
                ),
                'remaining_amount' => $data['grand_total'] - $data['paid_amount'],
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
            ]));

            $this->salesInvoiceRepo->createOrUpdateItems($salesInvoice->fresh(), $data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Invoice Penjualan');
        } catch (QueryException $e) {
            DB::rollBack();

            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesInvoice $salesInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesInvoice $salesInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesInvoice $salesInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesInvoice $salesInvoice)
    {
        //
    }
}
