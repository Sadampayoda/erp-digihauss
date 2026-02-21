<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCoaRequest;
use App\Models\Coa;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoaController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage;

    protected $model;
    public function __construct()
    {
        $this->model = new Coa();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ((bool) $request->select) {
            try {
                $query = $this->model
                    ->where('is_active', true)
                    ->where('is_postable', false);

                if ($request->filled('only_parent')) {
                    $query->whereNull('parent_id');
                }

                $data = $query
                    ->orderBy('code')
                    ->get()
                    ->map(function ($coa) {
                        return [
                            'id'   => $coa->id,
                            'name' => "{$coa->code} - {$coa->name}",
                        ];
                    });

                return $this->sendSuccess(
                    data: $data,
                    message: 'Berhasil mendapatkan Parent COA'
                );
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }
        }
        return view('dashboard.master.coa.index', [
            'coas' => $this->model->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCoaRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $parent = $data['parent_id']
                ? Coa::findOrFail($data['parent_id'])
                : null;


            $generated = $this->model->generate($parent);

            if ($data['is_postable'] && $generated['level'] < 3) {
                return $this->sendErrors(
                    message: 'Akun level atas tidak boleh menjadi akun transaksi.'
                );
            }

            $this->model->create(array_merge($data, [
                'code' => $generated['code'],
                'level' => $generated['level'],

            ]));
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Coa');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Coa $coa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coa $coa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCoaRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $coa = $this->existsWhereId($this->model,$id);

            if ($data['is_postable'] && $coa->level < 3) {
                return $this->sendErrors(
                    message: 'Akun level atas tidak boleh menjadi akun transaksi.'
                );
            }

            if (
                isset($data['parent_id']) &&
                $data['parent_id'] != $coa->parent_id
            ) {
                return $this->sendErrors(
                    message: 'Parent akun tidak boleh diubah.'
                );
            }

            $coa->update($data);

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Mengubah COA');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(
                message: $this->handleDatabaseError($e)
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coa $coa)
    {
        //
    }
}
