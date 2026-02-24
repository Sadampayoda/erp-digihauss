<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdvanceSaleRequest;
use App\Models\AdvanceSale;
use App\Models\Items;
use App\Repositories\AdvanceSaleRepository;
use App\Traits\ApiResponse;
use App\Traits\AutoNumberTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvanceSaleController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage, AutoNumberTransaction;
    protected $advanceSaleRepo, $model, $setupColumn;
    public function __construct(AdvanceSaleRepository $advanceSaleRepository)
    {
        $this->advanceSaleRepo = $advanceSaleRepository;
        $this->model = new AdvanceSale();
        $this->setupColumn = [
            'detail_id' => ['label' => ' ', 'type' => 'hidden'],
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
    public function index(Request $request)
    {

        if ((bool) $request->select) {
            try {
                if (! $request->filled('customer')) {
                    return $this->sendSuccess(
                        [],
                        message: 'Customer belum dipilih'
                    );
                }


                $data = $this->model
                    ->where('customer', $request->customer)
                    ->whereIn('status', $request->status)
                    ->whereHas('items', function ($q) {
                        $q->whereColumn(
                            'sales_invoice_items_quantity',
                            '<',
                            'quantity'
                        );
                    })
                    ->get();

                return $this->sendSuccess($data, message: 'Berhasil Mendapatkan data Uang Muka');
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }
        }
        return view('dashboard.sales.advance_sales.index', [
            'advance_sales' => $this->model->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('dashboard.sales.advance_sales.create', [
            'items' => Items::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAdvanceSaleRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $advanceSale = $this->model->create(array_merge($data, [
                'transaction_number' => $this->generateTransactionNumber(
                    model: AdvanceSale::class,
                    prefix: 'AS',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date'],
                ),
                'remaining_amount' => $data['sub_total'] - $data['service'],
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
            ]));

            $this->advanceSaleRepo->createOrUpdateItems($advanceSale->fresh(), $data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Uang Muka');
        } catch (QueryException $e) {
            DB::rollBack();

            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {

        try {
            $data = $this->existsWhereId($this->model, $id, ['items.item']);
            return $this->sendSuccess($data, message: 'Berhasil Mendapatkan data Uang Muka');
        } catch (Exception $e) {
            return $this->sendErrors(message: $e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data = $this->existsWhereId($this->model, $id);
        // dd($this->model->with('items.item')->find($id));
        return view('dashboard.sales.advance_sales.create', [
            'data' => $this->model->with('items.item')->find($id),
            'items' => Items::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateAdvanceSaleRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $advanceSale = $this->existsWhereId($this->model, $id);

            if (!$advanceSale->transaction_number) {
                $advanceSale->transaction_number = $this->generateTransactionNumber(
                    model: AdvanceSale::class,
                    prefix: 'AS',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date']
                );
            }

            $advanceSale->update([
                ...$data,
                'remaining_amount' => $data['sub_total'] - $data['advance_amount'],
            ]);

            $this->advanceSaleRepo->createOrUpdateItems($advanceSale->fresh(), $data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Mengupdate Uang Muka');
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

            $advanceSale = $this->existsWhereId($this->model, $id);

            if ($advanceSale->items()) {
                $advanceSale->items()->delete();
            }
            $advanceSale->delete();

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menghapus Uang Muka');
        } catch (QueryException $e) {

            DB::rollBack();

            return $this->sendErrors(
                message: $this->handleDatabaseError($e)
            );
        }
    }
}
