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
    protected $advanceSaleRepo, $model;
    public function __construct(AdvanceSaleRepository $advanceSaleRepository)
    {
        $this->advanceSaleRepo = $advanceSaleRepository;
        $this->model = new AdvanceSale();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.sales.advance_sales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setupColumn = [
            'image' => ['label' => 'Gambar','type' => 'image'],
            'name' => ['label' => 'Nama Produk'],
            'variant' => ['label' => 'Varian'],
            'sale_price' => ['label' => 'Harga Jual','edit' => true,'type' => 'number'],
            'purchase_price' => ['label' => 'Harga Beli','type' => 'number'],
            'quantity' => ['label' => 'Qty','edit' => true,'type' => 'number'],
            'service' => ['label' => 'Servis','edit' => true,'type' => 'number'],
            'sub_total' => ['label' => 'Sub Total','type' => 'number'],
            'margin' => ['label' => 'Margin','type' => 'number'],
            'margin_percentage' => ['label' => 'Margin (%)','type' => 'number'],
            'action' => ['label' => 'Action', 'delete' => true]
        ];
        return view('dashboard.sales.advance_sales.create',[
            'items' => Items::all(),
            'setupColumn' => $setupColumn
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
            $advanceSale = $this->model->create(array_merge($data,[
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

            $this->advanceSaleRepo->createItems($advanceSale->fresh(),$data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Brand');
        } catch (QueryException $e) {
            DB::rollBack();

            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AdvanceSale $advanceSale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdvanceSale $advanceSale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdvanceSale $advanceSale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdvanceSale $advanceSale)
    {
        //
    }
}
