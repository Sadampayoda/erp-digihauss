<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBankRequest;
use App\Http\Requests\CreateCashRequest;
use App\Models\Bank;
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

class BankController extends Controller
{
    use ApiResponse, Validate, HandleErroMessage, AutoNumberTransaction;
    protected $model;
    public function __construct()
    {
        $this->model = new Bank();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!in_array($request->type, [Bank::TYPE_IN, Bank::TYPE_OUT])) {
            abort(404);
        }

        return view('dashboard.finance.bank.index', [
            'banks' => $this->model->where('type', $request->type)->get(),
            'type' => $request->type,
            'recent_assets' => Bank::recentBankAsset(),
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
    public function store(CreateBankRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $bank = $this->model->create(array_merge($data, [
                'transaction_number' => $this->generateTransactionNumber(
                    model: Bank::class,
                    prefix:  $data['type'] == 'in' ? 'BI' : 'BO',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date'],
                ),
            ]));
            $this->settingJournal($bank);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Menambahkan Bank');
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

            $bank = $this->existsWhereId($this->model, $id);

            if (!$bank->transaction_number) {
                $bank->transaction_number = $this->generateTransactionNumber(
                    model: Bank::class,
                    prefix: $data['type'] == 'in' ? 'BI' : 'BO',
                    column: 'transaction_number',
                    transactionDate: $data['transaction_date']
                );
            }
            $bank->update($data);
            $this->settingJournal($bank);
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil Modifikasi Bank');
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
            $bank = $this->existsWhereId($this->model, $id);
            $this->settingJournal($bank, 'delete');
            $bank->delete();
            DB::commit();

            return $this->sendSuccess(message: 'Berhasil menghapus Bank');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrors(message: $e);
        }
    }

    protected function settingJournal($bank, $method = 'create')
    {
        $journal = app(JournalRepository::class);

        switch ($method) {
            case 'create':
                $module = $bank->type == 'in' ? 'bank-in' : 'bank-out';
                $action = $bank->type == 'in' ? 'bank_in' : 'bank_out';

                $coaDebit = $bank->coa_debit;
                $coaCredit = $bank->coa_credit;

                if ($bank->type == 'out') {
                    [$coaDebit, $coaCredit] = [$coaCredit, $coaDebit];
                }
                // return penjualan debit
                // kas / bank credit
                $journal->generateJournal(
                    data: $bank,
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
                $journal->destroyJournal($bank);
                break;
            default:
                break;
        }
    }
}
