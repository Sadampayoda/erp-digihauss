<?php


use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache as FacadesCache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    Log::info('closing day: '.now());
    $lock = FacadesCache::lock('auto-create-item-responsibility', 3600);

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
