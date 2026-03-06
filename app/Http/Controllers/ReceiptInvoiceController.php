<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReceiptInvoiceRequest;
use App\Models\Items;
use App\Models\ReceiptInvoice;
use App\Repositories\ReceiptInvoiceRepository;
use App\Traits\ApiResponse;
use App\Traits\AutoNumberTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptInvoiceController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage, AutoNumberTransaction;
    protected $receiptInvoiceRepo, $model, $setupColumn;

    public function __construct(ReceiptInvoiceRepository $receiptInvoiceRepository)
    {
        $this->receiptInvoiceRepo = $receiptInvoiceRepository;
        $this->model = new ReceiptInvoice();
        $this->setupColumn = [
            'detail_id' => ['label' => ' ', 'type' => 'hidden'],
            'advance_sale_items_id' => ['label' => ' ', 'type' => 'hidden'],
            'image' => ['label' => 'Gambar', 'type' => 'image'],
            'name' => ['label' => 'Nama Produk'],
            'variant' => ['label' => 'Varian'],
            'sale_price' => ['label' => 'Harga Jual', 'type' => 'number'],
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
                if (! $request->filled('vendor')) {
                    return $this->sendSuccess(
                        [],
                        message: 'Vendor belum dipilih'
                    );
                }


                $data = $this->model
                    ->where('vendor', $request->vendor)
                    ->whereIn('status', $request->status)
                    // ->whereHas('items', function ($q) {
                    //     $q->whereColumn(
                    //         'sales_invoice_items_quantity',
                    //         '<',
                    //         'quantity'
                    //     );
                    // })
                    ->get();

                return $this->sendSuccess($data, message: 'Berhasil Mendapatkan data Invoice Pembelian');
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }
        }
        return view('dashboard.purchasing.receipt_invoices.index', [
            'receipt_invoices' => $this->model->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.purchasing.receipt_invoices.create', [
            'items' => Items::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateReceiptInvoiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();



            $receiptInvoice = $this->model->create(array_merge($data, [
                'transaction_number' => $this->generateTransactionNumber(
                    model: ReceiptInvoice::class,
                    prefix: 'SI',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date'],
                ),
                'remaining_amount' => $data['remaining_amount'],
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
            ]));

            $this->receiptInvoiceRepo->createOrUpdateItems($receiptInvoice->fresh(), $data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Invoice Pembelian');
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
            return $this->sendSuccess($data, message: 'Berhasil Mendapatkan data Receipt Invoice');
        } catch (Exception $e) {
            return $this->sendErrors(message: $e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data = $this->existsWhereId($this->model, $id, ['items.item']);

        return view('dashboard.purchasing.receipt_invoices.create', [
            'data' => $data,
            'items' => Items::all(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateReceiptInvoiceRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $receiptInvoice = $this->existsWhereId($this->model, $id);

            if (!$receiptInvoice->transaction_number) {
                $receiptInvoice->transaction_number = $this->generateTransactionNumber(
                    model: ReceiptInvoice::class,
                    prefix: 'SI',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date']
                );
            }



            $receiptInvoice->update([
                ...$data,
                'remaining_amount' => $data['remaining_amount']
            ]);

            $this->receiptInvoiceRepo->createOrUpdateItems($receiptInvoice->fresh(), $data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Mengupdate Invoice Pembelian');
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

            $receiptInvoice = $this->existsWhereId($this->model, $id);

            $this->receiptInvoiceRepo->deleteItems($receiptInvoice);

            $receiptInvoice->delete();

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menghapus Invoice Pembelian');
        } catch (QueryException $e) {

            DB::rollBack();

            return $this->sendErrors(
                message: $this->handleDatabaseError($e)
            );
        }
    }
}
