<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Traits\Validate;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    use Validate;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $menuKey = $request->menu;
        $menuConfig = $menuKey ? config('menu.' . $menuKey, []) : [];

        $query = Journal::query();

        if (!empty($menuConfig)) {
            $query->whereIn('journal_type', array_keys($menuConfig));
        }

        $journals = $query->select([
        'journal_number',
    ])->groupBy('journal_number')->get();

        return view('dashboard.journals.index', [
            'journals' => $journals
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $transactionNumber)
    {
        $setupColumn = [
            'transaction_date' => ['label' => 'Tanggal Terbit'],
            'coa' => ['label' => 'Akun Coa'],
            'coa_name' => ['label' => 'Coa Nama'],
            'description' => ['label' => 'Uraian'],
            'debit' => ['label' => 'Debit', 'type' => 'number'],
            'credit' => ['label' => 'Kredit', 'type' => 'number'],
        ];
        $data = Journal::with(['details'])->where('journal_number',$transactionNumber)->get();
        return view('dashboard.journals.show',[
            'data' => $data,
            'setupColumn' => $setupColumn
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Journal $journal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Journal $journal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Journal $journal)
    {
        //
    }
}
