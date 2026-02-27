<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSalesReturnRequest;
use App\Models\Items;
use App\Models\SalesInvoice;
use App\Models\SalesReturn;
use App\Repositories\SalesReturnRepository;
use App\Traits\ApiResponse;
use App\Traits\AutoNumberTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReturnController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage, AutoNumberTransaction;
    protected $salesReturnRepo, $model, $setupColumn;

    public function __construct(SalesReturnRepository $salesReturnRepository)
    {
        $this->salesReturnRepo = $salesReturnRepository;
        $this->model = new SalesReturn();
        $this->setupColumn = [
            'detail_id' => ['label' => ' ', 'type' => 'hidden'],
            'sales_invoice_items_id' => ['label' => ' ', 'type' => 'hidden'],
            'image' => ['label' => 'Gambar', 'type' => 'image'],
            'name' => ['label' => 'Nama Produk'],
            'variant' => ['label' => 'Varian'],
            'sale_price' => ['label' => 'Harga Jual', 'edit' => true, 'type' => 'number'],
            'purchase_price' => ['label' => 'Harga Beli', 'type' => 'number'],
            'quantity' => ['label' => 'Qty', 'edit' => true, 'type' => 'number'],
            'si_quantity' => ['label' => 'Qty SI', 'type' => 'number'],
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
        return view('dashboard.sales.sales_returns.index', [
            'sales_returns' => $this->model->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.sales.sales_returns.create', [
            'items' => Items::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSalesReturnRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $salesInvoice = $this->existsWhereId(new SalesInvoice(), $data['sales_invoice_id']);



            $salesReturn = $this->model->create(array_merge($data, [
                'transaction_number' => $this->generateTransactionNumber(
                    model: SalesReturn::class,
                    prefix: 'SR',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date'],
                ),
                'remaining_amount' => $salesInvoice->grand_total - $data['paid_amount'],
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
            ]));

            $this->salesReturnRepo->createOrUpdateItems($salesReturn->fresh(), $data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Pengembalian Barang');
        } catch (QueryException $e) {
            DB::rollBack();

            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data = $this->existsWhereId($this->model, $id, ['items.item']);

        return view('dashboard.sales.sales_returns.create', [
            'data' => $data,
            'items' => Items::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateSalesReturnRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $salesReturn = $this->existsWhereId($this->model, $id);

            if (!$salesReturn->transaction_number) {
                $salesReturn->transaction_number = $this->generateTransactionNumber(
                    model: SalesReturn::class,
                    prefix: 'SR',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date']
                );
            }

            $salesInvoice = $this->existsWhereId(new SalesInvoice(), $data['sales_invoice_id']);

            $salesReturn->update([
                ...$data,
                'remaining_amount' => $salesInvoice->grand_total - $data['paid_amount'],
            ]);

            $this->salesReturnRepo->createOrUpdateItems($salesReturn->fresh(), $data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Mengupdate Pengembalian Barang');
        } catch (QueryException $e) {
            DB::rollBack();

            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();

            $salesReturn = $this->existsWhereId($this->model, $id);

            $this->salesReturnRepo->deleteItems($salesReturn);

            $salesReturn->delete();

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menghapus Pengembalian Barang');
        } catch (QueryException $e) {

            DB::rollBack();

            return $this->sendErrors(
                message: $this->handleDatabaseError($e)
            );
        }
    }
}
