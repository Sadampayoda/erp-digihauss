<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\ReceiptInvoice;
use App\Repositories\ReceiptInvoiceRepository;
use App\Traits\ApiResponse;
use App\Traits\AutoNumberTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReceiptInvoice $receiptInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReceiptInvoice $receiptInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReceiptInvoice $receiptInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReceiptInvoice $receiptInvoice)
    {
        //
    }
}
