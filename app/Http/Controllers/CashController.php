<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCashRequest;
use App\Models\Cash;
use App\Repositories\JournalRepository;
use App\Traits\ApiResponse;
use App\Traits\AutoNumberTransaction;
use App\Traits\HandleErroMessage;
use App\Traits\Validate;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage, AutoNumberTransaction;
    protected $model;
    public function __construct()
    {
        $this->model = new Cash();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!in_array($request->type, [Cash::TYPE_IN, Cash::TYPE_OUT])) {
            abort(404);
        }

        return view('dashboard.finance.cash.index', [
            'cashs' => $this->model->where('type', $request->type)->get(),
            'type' => $request->type,
            'recent_assets' => Cash::recentCashAsset(),
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
    public function store(CreateCashRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $cash = $this->model->create(array_merge($data, [
                'transaction_number' => $this->generateTransactionNumber(
                    model: Cash::class,
                    prefix:  $data['type'] == 'in' ? 'CI' : 'CO',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date'],
                ),
            ]));
            $this->settingJournal($cash);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Kas');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendErrors(message: $this->handleDatabaseError($e));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cash $cash)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cash $cash) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCashRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $cash = $this->existsWhereId($this->model, $id);

            if (!$cash->transaction_number) {
                $cash->transaction_number = $this->generateTransactionNumber(
                    model: Cash::class,
                    prefix: $data['type'] == 'in' ? 'CI' : 'CO',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date']
                );
            }
            $cash->update($data);
            $this->settingJournal($cash);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Modifikasi Kas');
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
            $cash = $this->existsWhereId($this->model, $id);
            $this->settingJournal($cash, 'delete');
            $cash->delete();
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil menghapus Kas');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrors(message: $e);
        }
    }

    protected function settingJournal($cash, $method = 'create')
    {
        $journal = app(JournalRepository::class);

        switch ($method) {
            case 'create':
                $module = $cash->type == 'in' ? 'cash-in' : 'cash-out';
                $action = $cash->type == 'in' ? 'cash_in' : 'cash_out';

                $coaDebit = $cash->coa_debit;
                $coaCredit = $cash->coa_credit;

                if ($cash->type == 'out') {
                    [$coaDebit, $coaCredit] = [$coaCredit, $coaDebit];
                }
                // return penjualan debit
                // kas / bank credit
                $journal->generateJournal(
                    data: $cash,
                    details: null,
                    module: $module,
                    action: $action,
                    columnPaymentMethod: 'payment_method',
                    columnContact: null,
                    columnDescription: 'description',
                    columnNominalDebit: 'paid_amount',
                    columnNominalCredit: 'paid_amount',
                    columnCoaDebit: $coaDebit,
                    columnCoaCredit: $coaCredit
                );


                break;
            case 'delete':
                $journal->destroyJournal($cash);
                break;
            default:
                break;
        }
    }
}
