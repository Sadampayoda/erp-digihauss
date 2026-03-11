<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemConditionRequest;
use App\Models\ItemCondition;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemConditionController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage;
    /**
     * Display a listing of the resource.
     */
    protected $model;

    public function __construct()
    {
        $this->model = new ItemCondition();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.items.conditions.index', [
            'conditions' => $this->model->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.items.conditions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateItemConditionRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->model->create($request->validated());
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Barang Kondisi');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemCondition $itemCondition)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        return view('dashboard.items.conditions.create', [
            'data' => $this->existsWhereId($this->model, $id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateItemConditionRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $itemDetail = $this->existsWhereId($this->model,$id);
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
    public function destroy(ItemCondition $itemCondition)
    {
        //
    }
}
