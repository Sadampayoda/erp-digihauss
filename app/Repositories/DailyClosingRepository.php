<?php

namespace App\Repositories;

use App\Models\DailyClosing;
use App\Models\DailyClosingItem;
use App\Models\DailyClosingResponsibility;
use App\Models\ItemResponsibility;
use App\Models\User;
use App\Traits\Validate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DailyClosingRepository
{
    use Validate;
    public function CreateClosingDayForItems($closingDay, $user)
    {
        $totalSales = 0;
        $totalPayment = 0;
        $totalHpp = 0;
        $totalQuantity = 0;
        $totalTransaction = 0;
        foreach ($user->salesInvoices as $salesInvoice) {

            if (!in_array($salesInvoice->status, [2, 3, 4])) {
                continue;
            }

            if ($salesInvoice->items->isEmpty()) {
                continue;
            }

            foreach ($salesInvoice->items as $item) {
                $salePrice = $item->sale_price ?? 0;
                $purchasePrice = $item->purchase_price ?? 0;
                $service = $item->service ?? 0;
                $qty = $item->qty ?? 1;

                $totalSales += $salePrice * $qty;
                $totalHpp += ($purchasePrice + $service) * $qty;
                $totalQuantity += $qty;

                DailyClosingItem::create([
                    'closing_id' => $closingDay->id,
                    'transaction_id' => $salesInvoice->id,
                    'transaction_detail_id' => $item->id,
                    'item_id' => $item->item_id,
                    'item_detail_id' => $item->item_detail_id,
                    'sale_price' => $salePrice,
                    'purchase_price' => $purchasePrice,
                    'service' => $service,
                    'notes' => 'sales-invoice',
                ]);

                $itemResponsibility = ItemResponsibility::where('item_detail_id', $item->item_detail_id)
                    ->whereDate('assigned_at', $closingDay->closed_at)
                    ->where('user_id', $closingDay->user_id)
                    ->first();

                DailyClosingResponsibility::create([
                    'item_responsibility_id' => $itemResponsibility->id,
                    'closing_id' => $closingDay->id,
                    'item_id' => $item->item_id,
                    'item_detail_id' => $item->item_detail_id,
                    'sale_price' => $salePrice,
                    'purchase_price' => $purchasePrice,
                    'service' => $service,
                    'notes' => 'sales-invoice',
                ]);
            }

            $totalPayment += ($salesInvoice->paid_amount ?? 0) + ($salesInvoice->advance_amount ?? 0);
            $totalTransaction += $salesInvoice->grand_total ?? 0;
        }

        $stockExpected = $user->itemResponsibility->count();

        $closingDay->update([
            'total_sales' => $totalSales,
            'total_payment' => $totalPayment,
            'total_hpp' => $totalHpp,
            'total_profit' => $totalSales - $totalHpp,
            'total_quantity' => $totalQuantity,
            'cash_expected' => $totalTransaction,
            'cash_actual' => $totalPayment,
            'cash_difference' => $totalPayment - $totalTransaction,
            'total_stock_expected' => $stockExpected,
            'total_stock_actual' => $totalQuantity,
            'total_stock_difference' => $totalQuantity - $stockExpected,
        ]);
    }

    public function syncClosingDay($request)
    {
        $dailyClosing = DailyClosing::with(['dailyClosingItems', 'dailyClosingResponsibility'])
            ->where('user_id', $request['user_id'])
            ->whereDate('transaction_date', $request['transaction_date'])
            ->firstOrFail();

        $date = $request['transaction_date'];

        $this->isLocked($dailyClosing);

        $user = User::with([
            'dailyClosingDay' => function ($q) use ($date) {
                $q->whereDate('transaction_date', $date);
            },
            'dailyClosingDay.dailyClosingItems',
            'dailyClosingDay.dailyClosingResponsibility',
            'salesInvoices' => function ($q) use ($date) {
                $q->whereDate('transaction_date', $date);
            },
            'salesInvoices.items',
            'itemResponsibility' => function ($q) use ($date) {
                $q->whereDate('assigned_at', $date);
            },
            'itemResponsibility.itemDetail'
        ])->find($request['user_id']);

        $dailyClosing->dailyClosingItems()->delete();
        $dailyClosing->dailyClosingResponsibility()->delete();

        $this->CreateClosingDayForItems($dailyClosing, $user);
    }


    protected function isLocked($dailyClosing)
    {
        if ($dailyClosing->is_locked) {
            throw new \Exception('Closing sudah dikunci');
        }

        $hours = (int) (setting('closing_day_lock_after_hours') ?? 0);

        $lockTime = Carbon::parse($dailyClosing->closed_at)
            ->addHours($hours);

        if (now()->greaterThan($lockTime)) {

            $dailyClosing->update([
                'locked_at' => $lockTime,
                'is_locked' => true,
            ]);

            throw new \Exception('Closing sudah melewati batas waktu');
        }
    }
}
