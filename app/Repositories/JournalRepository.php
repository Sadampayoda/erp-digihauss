<?php

namespace App\Repositories;

use App\Models\Coa;
use App\Models\Contact;
use Illuminate\Validation\ValidationException;
use App\Traits\Validate;
use App\Models\Items;
use App\Models\Journal;
use App\Models\PaymentMethod;
use App\Models\SettingCoa;

class JournalRepository
{
    use Validate;

    protected $model;
    public function __construct()
    {
        $this->model = new Journal();
    }

    public function generateJournal(
        $data,
        $details,
        $module,
        $action,
        $columnPaymentMethod,
        $columnContact,
        $columnDescription,
        $columnNominalDebit,
        $columnNominalCredit,
    ) {

        if ($data->status < 2) return;

        $relatedPaymentMethod = PaymentMethod::findOrFail($data->$columnPaymentMethod);

        $settingCoa = SettingCoa::where('module', $module)
            ->where('is_active', true)
            ->where('action', $action)
            ->where('payment_method', $data->$columnPaymentMethod)
            ->get();

        if ($settingCoa->isEmpty()) {
            throw ValidationException::withMessages([
                'journal' => 'Fitur ' . SettingCoa::$module[$module] . ' belum melakukan setting COA untuk aksi ' . SettingCoa::$action[$action] . ' dan pembayaran ' . $relatedPaymentMethod->name ?? null,
            ]);
        }


        $debitCoa = $settingCoa->where('position', 'debit')->first();
        if (!$debitCoa) {
            throw ValidationException::withMessages([
                'journal' => 'Fitur ' . SettingCoa::$module[$module] . ' belum melakukan setting COA aksi ' . SettingCoa::$action[$action] . ' untuk debit',
            ]);
        }

        $creditCoa = $settingCoa->where('position', 'credit')->first();
        if (!$creditCoa) {
            throw ValidationException::withMessages([
                'journal' => 'Fitur ' . SettingCoa::$module[$module] . ' belum melakukan setting COA aksi ' . SettingCoa::$action[$action] . ' untuk credit',
            ]);
        }

        $amountDebit  = $data->$columnNominalDebit;
        $amountCredit = $data->$columnNominalCredit;

        if ($amountDebit != $amountCredit) {
            throw ValidationException::withMessages([
                'journal' => 'Journal tidak balance',
            ]);
        }

        $relateContact = Contact::find($data->$columnContact);

        $journal = $this->model->updateOrCreate(
            [
                'journal_number' => $data->transaction_number,
                'journal_type' => $module,
                'journal_action' => $action,
            ],
            [
                'journal_date'     => $data->transaction_date,
                'reference_number' => $data->transaction_number,
                'contact'          => $relateContact->code ?? null,
                'description'      => $data->$columnDescription,
            ]
        );

        if ($journal) {
            $journal->details()->delete();
            $relatedCoaDebit = Coa::find($debitCoa->coa_id);
            $relatedCoaCredit = Coa::find($creditCoa->coa_id);
            if (!$relatedCoaDebit || !$relatedCoaCredit) {
                throw ValidationException::withMessages([
                    'journal' => 'Coa tidak ditemukan, silahkan atur Coa dan setting',
                ]);
            }

            // Debit

            [$debit, $credit] = $this->normalizeAmount($amountDebit, 0);

            $journal->details()->create([
                'coa'         => $relatedCoaDebit->code,
                'coa_name'    => $relatedCoaDebit->name,
                'debit'       => $debit,
                'credit'      => $credit,
                'description' => $data->$columnDescription,
            ]);

            // Credit
            [$debit, $credit] = $this->normalizeAmount(0, $amountCredit);

            $journal->details()->create([
                'coa'         => $relatedCoaCredit->code,
                'coa_name'    => $relatedCoaCredit->name,
                'debit'       => $debit,
                'credit'      => $credit,
                'description' => $data->$columnDescription,
            ]);


            $this->summaryTotalDebitCredit($journal);
        }
    }

    public function destroyJournal(
        $data
    ) {
        if ($data->status > 2) return;

        $journals = $this->model
            ->where('journal_number', $data->transaction_number)
            ->get();

        if ($journals->isNotEmpty()) {
            foreach ($journals as $journal) {
                $journal->details()->delete();
                $journal->delete();
            }
        }
    }

    protected function summaryTotalDebitCredit($journal)
    {
        $journal = $journal->fresh();
        $journal->total_debit = $journal->details()->whereNull('deleted_at')->sum('debit');
        $journal->total_credit = $journal->details()->whereNull('deleted_at')->sum('credit');

        if ($journal->total_debit != $journal->total_credit) {
            throw ValidationException::withMessages([
                'journal' => 'Journal tidak balance',
            ]);
        }

        $journal->save();
    }

    protected function normalizeAmount(?float $debit = 0, ?float $credit = 0): array
    {
        $debit  = (float) ($debit ?? 0);
        $credit = (float) ($credit ?? 0);

        if ($debit < 0) {
            $credit += abs($debit);
            $debit = 0;
        }

        if ($credit < 0) {
            $debit += abs($credit);
            $credit = 0;
        }

        return [$debit, $credit];
    }
}
