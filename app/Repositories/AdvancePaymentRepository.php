<?php

namespace App\Repositories;

use App\Models\AdvancePaymentItems;
use Illuminate\Validation\ValidationException;
use App\Traits\Validate;
use App\Models\Items;

class AdvancePaymentRepository
{
    use Validate;
    public function createOrUpdateItems($advancePayment, $data)
    {
        // Handle Payment Amount
        $this->ensureInvoicePaidIn($advancePayment);

        // Handle Create Items
        $ids = [];
        foreach ($data['items'] as $item) {

            $relatedItem = $this->existsWhereId(new Items(), $item['item_id']);

            if ($item['detail_id']) {
                $detail = $advancePayment->items()->where('id', $item['detail_id'])->firstOrFail();

                if ($detail) {
                    $detail->update(array_merge($item, [
                        'item_name' => $item['name'],
                        'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                    ]));
                }

                $ids[] = $detail->id;
            } else {

                $detail = advancePaymentItems::create(array_merge($item, [
                    'coa' => 1,
                    'advance_payment_id' => $advancePayment->id,
                    'item_name'       => $item['name'],
                    'item_code'       => $relatedItem->code ?? $relatedItem->item_code,
                    'created_by' => 0,
                    'updated_by' => 0,
                    'deleted_by' => 0,
                ]));


                $ids[] = $detail->id;
            }
        }

        $advancePayment->items()->whereNotIn('id', $ids)->delete();

        $this->settingJournal($advancePayment);
    }

    protected function ensureInvoicePaidIn($advancePayment)
    {

        if ($advancePayment->advance_amount <= 0) {
            throw ValidationException::withMessages([
                'advance_amount' => 'Jumlah pembayaran harus lebih dari 0.'
            ]);
        }

        if ($advancePayment->advance_amount > $advancePayment->grand_total) {
            throw ValidationException::withMessages([
                'advance_amount' => 'Jumlah bayar tidak boleh lebih dari total pembayaran.',
            ]);
        }
    }


    public function settingJournal($advancePayment, $method = 'create')
    {
        $journal = app(JournalRepository::class);

        switch ($method) {
            case 'create':
                $journal->generateJournal(
                    data: $advancePayment,
                    details: $advancePayment->items,
                    module: 'advance-payment',
                    action: 'purchase_advance',
                    columnPaymentMethod: 'payment_method',
                    columnContact: 'vendor',
                    columnDescription: 'description',
                    columnNominalDebit: 'advance_amount',
                    columnNominalCredit: 'advance_amount'
                );
                break;
            case 'delete':
                $journal->destroyJournal($advancePayment);
                break;
            default:
                break;
        }
    }
}
