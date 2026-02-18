<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBrandRequest;
use App\Models\Brand;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage;
    protected $model;
    public function __construct()
    {
        $this->model = new Brand();
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
        return view('dashboard.master.brand.index', [
            'brands' => Brand::all()
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
    public function store(CreateBrandRequest $request)
    {
        try {
            DB::beginTransaction();
            Brand::create([
                'code'   => strtoupper($request->code),
                'name'   => $request->name,
            ]);
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
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateBrandRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $brand = $this->existsWhereId($this->model,$id);

            $brand->update([
                'code'   => strtoupper($request->code),
                'name'   => $request->name,
            ]);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil memperbarui Brand');
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
            $brand = $this->existsWhereId($this->model,$id);

            $brand->delete();
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil menghapus Brand');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrors(message: $e);
        }
    }
}
