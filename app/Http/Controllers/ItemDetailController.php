<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemDetailRequest;
use App\Models\ItemDetail;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemDetailController extends Controller
{

    use ApiResponse, Validate, HandleErroMessage;
    /**
     * Display a listing of the resource.
     */
    protected $model;

    public function __construct()
    {
        $this->model = new ItemDetail();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ((bool) $request->select) {
            try {
                $data = $this->model
                    ->with(['item'])
                    ->whereDoesntHave('condition')
                    ->get();


                $data = $data->map(function ($item) {
                    $id = $item->id;
                    $name = $item->item?->name ?? null;
                    return [
                        'id'   => $id,
                        'name' => "{$name} - {$item->serial_number}",
                    ];
                });
                return $this->sendSuccess($data, message: 'Berhasil Mendapatkan Barang Detail');
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }
        }

        if ((bool) $request->transaction) {
            try {
                $data = $this->model
                    ->with(['item','condition'])
                    ->find($request->items);

                return $this->sendSuccess($data, message: 'Berhasil Mendapatkan Barang Detail');
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }
        }
        return view('dashboard.items.details.index', [
            'details' => $this->model->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.items.details.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateItemDetailRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->model->create($request->validated());
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Barang detail');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemDetail $itemDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        return view('dashboard.items.details.create', [
            'data' => $this->existsWhereId($this->model, $id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateItemDetailRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $itemDetail = $this->existsWhereId($this->model, $id);
            $itemDetail->update($request->validated());
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Modifikasi Barang detail');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemDetail $itemDetail)
    {
        //
    }
}
