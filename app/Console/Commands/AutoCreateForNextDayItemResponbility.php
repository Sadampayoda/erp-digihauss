<?php

namespace App\Console\Commands;

use App\Models\ItemResponsibility;
use App\Models\User;
use App\Traits\Loggable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoCreateForNextDayItemResponbility extends Command
{
    use Loggable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-create-for-next-day-item-responbility';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create data assign user for next day';

    protected function logChannel(): string
    {
        return 'stack';
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->logStart('Auto Create Item Responsibility');

        try {
            $yesterday = Carbon::yesterday();

            $users = User::with([
                'itemResponsibility' => function ($q) use ($yesterday) {
                    $q->whereDate('assigned_at', $yesterday);
                },
                'itemResponsibility.itemDetail',
                'itemResponsibility.item'
            ])->get();


            $totalInsert = 0;

            DB::transaction(function () use ($users, &$totalInsert) {

                foreach ($users as $user) {

                    if ($user->itemResponsibility->isEmpty()) {
                        continue;
                    }

                    foreach ($user->itemResponsibility as $sibility) {

                        if (!$sibility->itemDetail) continue;

                        if (!in_array($sibility->itemDetail?->status, [0, 1, 2])) {
                            continue;
                        }

                        $exists = ItemResponsibility::where('user_id', $user->id)
                            ->where('item_detail_id', $sibility->itemDetail->id)
                            ->whereDate('assigned_at', today())
                            ->exists();

                        if (!$exists) {
                            ItemResponsibility::create([
                                'user_id' => $user->id,
                                'item_id' => $sibility->item?->id,
                                'item_detail_id' => $sibility->itemDetail?->id,
                                'assigned_at' => now(),
                            ]);

                            $totalInsert++;

                            Log::info('Insert item responsibility', [
                                'user_id' => $user->id,
                                'item_detail_id' => $sibility->itemDetail->id,
                            ]);
                        }
                    }
                }
            });

            $this->logSuccess('Auto Create Item Responsibility', [
                'total_insert' => $totalInsert
            ]);

            $this->info("Success! Total insert: {$totalInsert}");
        } catch (\Throwable $e) {

            $this->logFailed('Auto Create Item Responsibility', $e);

            $this->error('Error: ' . $e->getMessage());
        }
    }
}
