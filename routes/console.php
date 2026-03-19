<?php


use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:auto-create-for-next-day-item-responbility')
    ->dailyAt(setting('closing_day_time'))
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/cron.log'));


Schedule::call(function () {
    Log::info('Cron jalan: ' . now());
})->everyMinute();
