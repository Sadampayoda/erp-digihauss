<?php

namespace App\Console\Commands;

use App\Models\DailyClosing;
use App\Models\DailyClosingItem;
use App\Models\DailyClosingResponsibility;
use App\Models\User;
use App\Repositories\ClosingDayRepository;
use App\Repositories\DailyClosingRepository;
use App\Traits\Loggable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoClosingDayCommand extends Command
{
    use Loggable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-closing-day-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Closing day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->logStart('Auto Create Closing Day');

        try {
            $users = User::with([
                'dailyClosingDay' => function ($q) {
                    $q->whereDate('transaction_date', today());
                },
                'salesInvoices' => function ($q) {
                    $q->whereDate('transaction_date', today());
                },
                'salesInvoices.items',
                'itemResponsibility' => function ($q) {
                    $q->whereDate('assigned_at', today());
                },
                'itemResponsibility.itemDetail'
            ])->get();

            $totalInsert = 0;
            $closingDayRepository = app(DailyClosingRepository::class);
            DB::transaction(function () use ($users, &$totalInsert, $closingDayRepository) {

                foreach ($users as $user) {

                    if ($user->dailyClosingDay->isNotEmpty()) {
                        continue;
                    }

                    $exists = DailyClosing::where('user_id', $user->id)
                        ->whereDate('transaction_date', today())
                        ->exists();

                    if ($exists) {
                        continue;
                    }



                    $closing = DailyClosing::create([
                        'transaction_date' => today(),
                        'user_id' => $user->id,
                        'total_sales' => 0,
                        'total_payment' => 0,
                        'total_hpp' => 0,
                        'total_profit' => 0,
                        'total_quantity' => 0,
                        'cash_expected' => 0,
                        'cash_actual' => 0,
                        'cash_difference' => 0,
                        'total_stock_expected' => 0,
                        'total_stock_actual' => 0,
                        'total_stock_difference' => 0,
                        'status' => 0,
                        'closed_at' => now(),
                        'notes' => 'Closing day tanggal ' . now(),
                    ]);

                    $closingDayRepository->CreateClosingDayForItems($closing, $user);

                    $totalInsert++;
                }
            });

            $this->logSuccess('Auto Create Closing Day', [
                'total_insert' => $totalInsert
            ]);

            $this->info("Success! Total insert: {$totalInsert}");
        } catch (\Throwable $e) {
            $this->logFailed('Auto Create Closing Day', $e);

            $this->error('Error: ' . $e->getMessage());
        }
    }
}
