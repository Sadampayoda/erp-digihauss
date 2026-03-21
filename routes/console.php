<?php


use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use Opcodes\LogViewer\Facades\Cache;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:auto-create-for-next-day-item-responbility')
    ->dailyAt(setting('closing_day_time'))
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/cron.log'));

Schedule::call(function () {
    $lock = Cache::lock('auto-create-item-responsibility', 3600);

    if (!$lock->get()) {
        return;
    }

    try {
        Artisan::call('app:auto-create-for-next-day-item-responbility');
    } finally {
        $lock->release();
    }
})->dailyAt(setting('closing_day_time'));

Schedule::call(function () {
    Log::info('Cron jalan: ' . now());
})->everyMinute();
