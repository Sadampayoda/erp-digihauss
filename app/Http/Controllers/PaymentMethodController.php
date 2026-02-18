<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentMethodRequest;
use App\Models\PaymentMethod;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage;
    protected $model;
    public function __construct()
    {
        $this->model = new PaymentMethod();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ((bool) $request->select) {
            try {
                return $this->sendSuccess($this->model->all(),message: 'Berhasil Mendapatkan Metode Pembayaran');
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }

        }
        return view('dashboard.master.payment_method.index', [
            'brands' => $this->model->all(),
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
    public function store(CreatePaymentMethodRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->model->create([
                'code'   => strtoupper($request->code),
                'name'   => $request->name,
            ]);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan metode pembayaran');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePaymentMethodRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $paymentMethod = $this->existsWhereId($this->model,$id);

            $paymentMethod->update([
                'code'   => strtoupper($request->code),
                'name'   => $request->name,
            ]);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil memperbarui metode pembayaran');
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
            $paymentMethod = $this->existsWhereId($this->model,$id);

            $paymentMethod->delete();
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil menghapus metode pembayaran');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrors(message: $e);
        }
    }
}
