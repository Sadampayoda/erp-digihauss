<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSeriesRequest;
use App\Models\Series;
use App\Traits\ApiResponse;
use App\Traits\Validate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeriesController extends Controller
{
    use ApiResponse, Validate;
    protected $model;

    public function __construct()
    {
        $this->model = new Series();
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
        return view('dashboard.master.series.index', [
            'series' => Series::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSeriesRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->model->create([
                'code'   => strtoupper($request->code),
                'name'   => $request->name,
                'release_year' => $request->release_year,
                'is_active' => 1,
            ]);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Series');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrors(message: $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Series $series)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Series $series)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            DB::beginTransaction();
            $brand = $this->existsWhereId($this->model, $id);

            $brand->update([
                'code'   => strtoupper($request->code),
                'name'   => $request->name,
                'release_year' => $request->release_year,
                'is_active' => 1,
            ]);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil memperbarui Series');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrors(message: $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();
            $brand = $this->existsWhereId($this->model, $id);

            $brand->delete();
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil menghapus Series');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrors(message: $e);
        }
    }
}
