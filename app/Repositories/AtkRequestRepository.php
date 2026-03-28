<?php

namespace App\Repositories;

use App\Models\Item;
use Illuminate\Validation\ValidationException;
use App\Traits\Validate;


class AtkRequestRepository
{
    use Validate;
    public function createOrUpdateItems($atkRequest, $data)
    {
        // Handle Create Items
        $ids = [];
        foreach ($data['items'] as $item) {
            $relatedItem = $this->existsWhereId(new Item(), $item['item_id']);

            if ($item['detail_id']) {
                $detail = $atkRequest->items()->where('id', $item['detail_id'])->firstOrFail();

                if ($detail) {
                    $detail->update(array_merge($item, [
                        'item_name' => $relatedItem->name,
                        'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                        'unit_id' => $relatedItem->unit_id
                    ]));
                }

                $ids[] = $detail->id;
            } else {

                $detail = $atkRequest->items()->create(array_merge($item, [
                    'atk_request_id' => $atkRequest->id,
                    'item_name' => $relatedItem->name,
                    'item_code' => $relatedItem->code ?? $relatedItem->item_code,
                    'unit_id' => $relatedItem->unit_id
                ]));



                $ids[] = $detail->id;
            }
        }

        $atkRequest->items()->whereNotIn('id', $ids)->delete();


        $atkRequest = $atkRequest->fresh();

        (new ItemRepositrory())->updateItemDetail($atkRequest);

        $this->settingJournal($atkRequest);
    }

    public function deleteItems($atkRequest)
    {
        $this->settingJournal($atkRequest, 'delete');

        (new ItemRepositrory())->updateItemDetail($atkRequest);
        if ($atkRequest->items()) {
            $atkRequest->items()->delete();
        }
    }

    public function settingJournal($atkRequest, $method = 'create')
    {
        $journal = app(JournalRepository::class);

        switch ($method) {
            case 'create':
                if ($atkRequest->paid_amount > 0) {
                    $journal->generateJournal(
                        data: $atkRequest,
                        details: $atkRequest->items,
                        module: 'atk-request',
                        action: 'office_supplies',
                        columnPaymentMethod: 'payment_method',
                        columnContact: 'vendor',
                        columnDescription: 'description',
                        columnNominalDebit: 'paid_amount',
                        columnNominalCredit: 'paid_amount'
                    );
                }

                break;
            case 'delete':
                $journal->destroyJournal($atkRequest);
                break;
            default:
                break;
        }
    }
}
