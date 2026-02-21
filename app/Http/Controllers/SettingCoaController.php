<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSettingCoaRequest;
use App\Models\SettingCoa;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingCoaController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage;

    protected $model;
    public function __construct()
    {
        $this->model = new SettingCoa();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.settings.coa.index', [
            'settings' => $this->model->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.settings.coa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSettingCoaRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->model->create(array_merge($request->validated(),[
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 1,
            ]));
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Konfigurasi Coa');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SettingCoa $settingCoa) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        return view('dashboard.settings.coa.create', [
            'data' => $this->existsWhereId($this->model, $id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateSettingCoaRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $settingCoa = $this->existsWhereId($this->model, $id);

            $settingCoa->update($request->validated());
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil memperbarui Konfigurasi Coa');
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
            $settingCoa = $this->existsWhereId($this->model,$id);

            $settingCoa->delete();
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil menghapus Konfigurasi Coa');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrors(message: $e);
        }
    }
}
