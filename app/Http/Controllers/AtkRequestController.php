<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAtkRequest;
use App\Models\AtkRequest;
use App\Models\Item;
use App\Repositories\AtkRequestRepository;
use App\Traits\ApiResponse;
use App\Traits\AutoNumberTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AtkRequestController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage, AutoNumberTransaction;
    protected $atkRequestRepo, $model, $setupColumn;

    public function __construct(AtkRequestRepository $atkRequestRepository)
    {
        $this->atkRequestRepo = $atkRequestRepository;
        $this->model = new AtkRequest();
        $this->setupColumn = [
            'detail_id' => ['label' => ' ', 'type' => 'hidden'],
            'name' => ['label' => 'Nama Produk'],
            'sale_price' => ['label' => '', 'type' => 'hidden'],
            'price' => ['label' => 'Harga ATK', 'edit' => true, 'type' => 'number'],
            'quantity_requested' => ['label' => 'Qty Request', 'edit' => true, 'type' => 'number'],
            'quantity_approved' => ['label' => 'Qty diterima', 'edit' => true, 'type' => 'number'],
            'unit' => ['label' => 'Unit'],
            'unit_id' => ['label' => '', 'type' => 'hidden'],
            'sub_total' => ['label' => 'Sub Total', 'type' => 'number'],
            'action' => ['label' => 'Action', 'delete' => true],
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.general-affairs.atk.index', [
            'atks' => $this->model->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.general-affairs.atk.create', [
            'items' => Item::where('type', 0)->get(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAtkRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $paidAmount = collect($data['items'])->reduce(function ($total, $item) {
                return $total + ($item['quantity_approved'] * $item['price']);
            }, 0);

            $atkRequest = $this->model->create(array_merge($data, [
                'transaction_number' => $this->generateTransactionNumber(
                    model: AtkRequest::class,
                    prefix: 'ATK',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date'],
                ),
                'paid_amount' =>  $paidAmount,
                'approved_by' => $data['status'] == 2 ? auth()->user()->id : null,
                'approved_at' => $data['status'] == 2 ? Carbon::now() : null
            ]));

            $this->atkRequestRepo->createOrUpdateItems($atkRequest->fresh(), $data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan ATK');
        } catch (QueryException $e) {
            DB::rollBack();

            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AtkRequest $atkRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data = $this->existsWhereId($this->model, $id, ['items.item']);

        return view('dashboard.general-affairs.atk.create', [
            'data' => $data,
            'items' => Item::where('type', 0)->get(),
            'setupColumn' => $this->setupColumn
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateAtkRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $atkRequest = $this->existsWhereId($this->model, $id);
            $this->allowTransaction($atkRequest->status);

            if (!$atkRequest->transaction_number) {
                $atkRequest->transaction_number = $this->generateTransactionNumber(
                    model: AtkRequest::class,
                    prefix: 'ATK',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date']
                );
            }

            $paidAmount = collect($data['items'])->reduce(function ($total, $item) {
                return $total + ($item['quantity_approved'] * $item['price']);
            }, 0);
            $atkRequest->update([
                ...$data,
                'paid_amount' =>  $paidAmount,
                'approved_by' => $data['status'] == 2 ? auth()->user()->id : null,
                'approved_at' => $data['status'] == 2 ? Carbon::now() : null
            ]);

            $this->atkRequestRepo->createOrUpdateItems($atkRequest->fresh(), $data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Mengupdate ATK');
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

            $atkRequest = $this->existsWhereId($this->model, $id);

            $this->atkRequestRepo->deleteItems($atkRequest);

            $atkRequest->delete();

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menghapus ATK');
        } catch (QueryException $e) {

            DB::rollBack();

            return $this->sendErrors(
                message: $this->handleDatabaseError($e)
            );
        }
    }
}
