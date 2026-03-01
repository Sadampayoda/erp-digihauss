<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereHas('permissions')
            ->withCount('permissions')
            ->get();
        return view('dashboard.master.permissions.index', [
            'permissions' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $moduleLabels = [];

        foreach (config('menu') as $prefix => $modules) {
            foreach ($modules as $key => $label) {
                $moduleLabels["{$prefix}.{$key}"] = $label;
            }
        }
        return view('dashboard.master.permissions.create', [
            'permissions' => Permission::orderBy('module')
                ->orderBy('action')
                ->get()
                ->groupBy('module'),
            'moduleLabels' => $moduleLabels,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePermissionRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $user = $this->existsWhereId(new User(), $data['user_id']);
            if ($user->permissions()->exists()) {
                return $this->sendErrors(
                    message: 'User ini sudah memiliki hak akses'
                );
            }
            $user->syncPermissions($request->permissions);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Hak akses');
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
    public function edit(int $id)
    {
        $moduleLabels = [];

        $user = $this->existsWhereId(new User(), $id);

        foreach (config('menu') as $prefix => $modules) {
            foreach ($modules as $key => $label) {
                $moduleLabels["{$prefix}.{$key}"] = $label;
            }
        }
        return view('dashboard.master.permissions.create', [
            'permissions' => Permission::orderBy('module')
                ->orderBy('action')
                ->get()
                ->groupBy('module'),
            'moduleLabels' => $moduleLabels,
            'data' => $user,
            'userPermissions' => $user->permissions->pluck('name')->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePermissionRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $user = $this->existsWhereId(new User(), $id);

            $user->syncPermissions($data['permissions']);

            DB::commit();

            return $this->sendSuccess(
                message: 'Berhasil Memperbarui Hak Akses'
            );
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
