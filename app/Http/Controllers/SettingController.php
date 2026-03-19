<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Traits\ApiResponse;
use App\Traits\Validate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    use ApiResponse, Validate;
    protected $model;
    public function __construct()
    {
        $this->model = new Setting();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $settings = $this->model
            ->when($request->group, fn($q) => $q->where('group', $request->group))
            ->orderBy('sort_order')
            ->get();

        return view('dashboard.settings.base.index', [
            'settings' => $settings,
            'title' => $request->group ?? "All"
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
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            foreach ($request->input('settings', []) as $key => $value) {
                $this->model->where('key', $key)->update([
                    'value' => $value,
                ]);
            }
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Modifikasi Setting');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrors(message: $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
