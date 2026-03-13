<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdvancePaymentRequest;
use App\Models\AdvancePayment;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\Items;
use App\Repositories\AdvancePaymentRepository;
use App\Traits\ApiResponse;
use App\Traits\AutoNumberTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvancePaymentController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage, AutoNumberTransaction;
    protected $advancePaymentRepo, $model, $setupColumn;
    public function __construct(AdvancePaymentRepository $advancePaymentRepository)
    {
        $this->advancePaymentRepo = $advancePaymentRepository;
        $this->model = new AdvancePayment();
        $this->setupColumn = [
            'detail_id' => ['label' => ' ', 'type' => 'hidden'],
            'image' => ['label' => 'Gambar', 'type' => 'image'],
            'name' => ['label' => 'Nama Produk'],
            'serial_number' => ['label' => 'Seri'],
            'purchase_price' => ['label' => 'Harga Beli','edit' => true, 'type' => 'number'],
            'sale_price' => ['label' => 'Harga Jual','edit' => true],
            'quantity' => ['label' => 'Qty', 'type' => 'number'],
            'service' => ['label' => 'Servis', 'edit' => true, 'type' => 'number'],
            'sub_total' => ['label' => 'Sub Total', 'type' => 'number'],
            'margin' => ['label' => 'Margin', 'type' => 'number'],
            'margin_percentage' => ['label' => 'Margin (%)', 'type' => 'number'],
            'action' => ['label' => 'Action', 'delete' => true],
            'item_detail_id' => ['label' => ' ', 'type' => 'hidden'],
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ((bool) $request->select) {
            try {
                if (! $request->filled('vendor')) {
                    return $this->sendSuccess(
                        [],
                        message: 'Vendor belum dipilih'
                    );
                }


                $data = $this->model
                    ->where('vendor', $request->vendor)
                    ->whereIn('status', $request->status)
                    ->whereHas('items', function ($q) {
                        $q->whereColumn(
                            'receipt_invoice_items_quantity',
                            '<',
                            'quantity'
                        );
                    })
                    ->get();

                return $this->sendSuccess($data, message: 'Berhasil Mendapatkan data Uang Muka Pembelian');
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }
        }
        return view('dashboard.purchasing.advance_payments.index', [
            'advance_payments' => $this->model->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.purchasing.advance_payments.create', [
            'items' => ItemDetail::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAdvancePaymentRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $advancePayment = $this->model->create(array_merge($data, [
                'transaction_number' => $this->generateTransactionNumber(
                    model: AdvancePayment::class,
                    prefix: 'AP',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date'],
                ),
                'grand_total' => $data['sub_total'] + $data['service'],
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
            ]));

            $this->advancePaymentRepo->createOrUpdateItems($advancePayment->fresh(), $data);
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
        return view('dashboard.purchasing.advance_payments.create', [
            'data' => $this->model->with('items.item.details')->find($id),
            'items' => ItemDetail::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateAdvancePaymentRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $advanceSale = $this->existsWhereId($this->model, $id);

            if (!$advanceSale->transaction_number) {
                $advanceSale->transaction_number = $this->generateTransactionNumber(
                    model: AdvancePayment::class,
                    prefix: 'AS',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date']
                );
            }

            $advanceSale->update([
                ...$data,
                'grand_total' => $data['sub_total'] - $data['advance_amount'],
            ]);

            $this->advancePaymentRepo->createOrUpdateItems($advanceSale->fresh(), $data);
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

            $advancePayment = $this->existsWhereId($this->model, $id);

            $this->advancePaymentRepo->settingJournal($advancePayment, 'delete');

            if ($advancePayment->items()->exists()) {
                $advancePayment->items()->delete();
            }

            $advancePayment->delete();

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
