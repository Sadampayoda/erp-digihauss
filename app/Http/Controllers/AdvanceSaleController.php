<?php

namespace App\Http\Controllers;

use App\Models\AdvanceSale;
use Illuminate\Http\Request;

class AdvanceSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.sales.advance_sales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.sales.advance_sales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AdvanceSale $advanceSale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdvanceSale $advanceSale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdvanceSale $advanceSale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdvanceSale $advanceSale)
    {
        //
    }
}
