<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use Validate, ApiResponse, HandleErroMessage;

    protected $model;
    public function __construct()
    {
        $this->model = new User();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ((bool) $request->select) {
            try {
                return $this->sendSuccess($this->model->all(),message: 'Berhasil Mendapatkan User');
            } catch (Exception $e) {
                return $this->sendErrors(message: $e);
            }

        }
        return view('dashboard.master.user.index', [
            'users' => $this->model->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.master.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            unset($data['current_password'], $data['password_confirmation']);

            $this->model->create($data);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan User');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('dashboard.master.user.create', [
            'data' => $this->existsWhereId($this->model, $id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateUserRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $user = $this->existsWhereId($this->model, $id);
            $data = $request->validated();

            if ($request->filled('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return $this->sendErrors(
                        message: 'Password lama tidak sesuai'
                    );
                }

                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            unset($data['current_password'], $data['password_confirmation']);

            $user->update($data);

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Mengubah User');
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
    public function destroy(string $id)
    {
        //
    }
}
