<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBrandRequest;
use App\Http\Requests\CreateUnitRequest;
use App\Models\Unit;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage;
    protected $model;
    public function __construct()
    {
        $this->model = new Unit();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ((bool) $request->select) {
            try {
                return $this->sendSuccess($this->model->all(),message: 'Berhasil Mendapatkan Brand');
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }

        }
        return view('dashboard.master.unit.index', [
            'units' => $this->model->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUnitRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->model->create($request->validated());
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Unit');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateUnitRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $unit = $this->existsWhereId($this->model,$id);

            $unit->update($request->validated());
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil memperbarui Unit');
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
            $unit = $this->existsWhereId($this->model,$id);

            $unit->delete();
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil menghapus Unit');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }
}
