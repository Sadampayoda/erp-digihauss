<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceRequest;
use App\Models\ItemDetail;
use App\Models\Journal;
use App\Repositories\JournalRepository;
use App\Traits\ApiResponse;
use App\Traits\AutoNumberTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage, AutoNumberTransaction;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.services.items.index', [
            'item_details' => ItemDetail::where('service', '>', 0)->get()
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
    public function store(CreateServiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (object) $request->validated();

            $transaction_number = $this->generateTransactionNumber(
                model: Journal::class,
                prefix: 'SER',
                column: 'journal_number',
                transactionDate: $data->transaction_date,
            );

            $data->transaction_number = $transaction_number;
            $data->status = 2;

            $this->settingJournal($data);

            $itemDetail = $this->existsWhereId(new ItemDetail(), $data->item_detail_id);

            $itemDetail->update([
                'service' => $data->service,
                'reference_service' => $data->transaction_number,
            ]);

            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Service');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $itemDetail = $this->existsWhereId(new ItemDetail(), $id);

            if ($itemDetail->status !== 1) {
                throw ValidationException::withMessages([
                    'status' => 'Barang yang sudah di service tidak dapat dihapus. Hanya barang dengan status In Stock yang boleh dihapus.',
                ]);
            }

            $itemDetail->status = 2;
            $itemDetail->transaction_number = $itemDetail->reference_service;

            $this->settingJournal($itemDetail, 'delete');

            $itemDetail->update([
                'service' => 0,
                'reference_service' => null,
            ]);


            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menghapus Service');
        } catch (QueryException $e) {

            DB::rollBack();

            return $this->sendErrors(
                message: $this->handleDatabaseError($e)
            );
        }
    }

    public function settingJournal($service, $method = 'create')
    {
        $journal = app(JournalRepository::class);

        switch ($method) {
            case 'create':
                // Persediaan debit
                // Hutang Usaha credit
                $journal->generateJournal(
                    data: $service,
                    details: [],
                    module: 'service',
                    action: 'service',
                    columnContact: null,
                    columnPaymentMethod: 'payment_method',
                    columnDescription: 'description',
                    columnNominalDebit: 'service',
                    columnNominalCredit: 'service'
                );
                break;
            case 'delete':
                $journal->destroyJournal($service);
                break;
            default:
                break;
        }
    }
}
