<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePurchaseReturnRequest;
use App\Models\ItemDetail;
use App\Models\PurchaseReturn;
use App\Models\ReceiptInvoice;
use App\Repositories\PurchaseReturnRepository;
use App\Traits\ApiResponse;
use App\Traits\AutoNumberTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage, AutoNumberTransaction;
    protected $purchaseReturnRepo, $model, $setupColumn;

    public function __construct(PurchaseReturnRepository $purchaseReturnRepository)
    {
        $this->purchaseReturnRepo = $purchaseReturnRepository;
        $this->model = new PurchaseReturn();
        $this->setupColumn = [
            'detail_id' => ['label' => ' ', 'type' => 'hidden'],
            'receipt_invoice_items_id' => ['label' => ' ', 'type' => 'hidden'],
            'image' => ['label' => 'Gambar', 'type' => 'image'],
            'name' => ['label' => 'Nama Produk'],
            'serial_number' => ['label' => 'Seri'],
            'sale_price' => ['label' => 'Harga Jual', 'edit' => true, 'type' => 'number'],
            'purchase_price' => ['label' => 'Harga Beli', 'type' => 'number'],
            'quantity' => ['label' => 'Qty', 'edit' => true, 'type' => 'number'],
            'ri_quantity' => ['label' => 'Qty RI', 'type' => 'number'],
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
    public function index()
    {
        return view('dashboard.purchasing.purchase_returns.index', [
            'purchase_returns' => $this->model->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.purchasing.purchase_returns.create', [
            'items' => ItemDetail::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePurchaseReturnRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $purchaseReturn = $this->model->create(array_merge($data, [
                'transaction_number' => $this->generateTransactionNumber(
                    model: PurchaseReturn::class,
                    prefix: 'PR',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date'],
                ),
                'remaining_amount' => $data['grand_total'] - $data['paid_amount'],
            ]));

            $this->purchaseReturnRepo->createOrUpdateItems($purchaseReturn->fresh(), $data);
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
    public function show(PurchaseReturn $purchaseReturn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data = $this->existsWhereId($this->model, $id, ['items.item.details']);

        return view('dashboard.purchasing.purchase_returns.create', [
            'data' => $data,
            'items' => ItemDetail::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePurchaseReturnRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $purchaseReturn = $this->existsWhereId($this->model, $id);
            $this->allowTransaction($purchaseReturn->status);

            if (!$purchaseReturn->transaction_number) {
                $purchaseReturn->transaction_number = $this->generateTransactionNumber(
                    model: PurchaseReturn::class,
                    prefix: 'SR',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date']
                );
            }

            $salesInvoice = $this->existsWhereId(new ReceiptInvoice(), $data['receipt_invoice_id']);

            $purchaseReturn->update([
                ...$data,
                'remaining_amount' => $data['grand_total'] - $data['paid_amount'],
            ]);

            $this->purchaseReturnRepo->createOrUpdateItems($purchaseReturn->fresh(), $data);
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

            $purchaseReturn = $this->existsWhereId($this->model, $id);

            $this->purchaseReturnRepo->deleteItems($purchaseReturn);

            $purchaseReturn->delete();

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
