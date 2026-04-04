<?php


use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache as FacadesCache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    Log::info('closing day: ' . now());

    if (App::environment('local')) {
        Artisan::call('app:auto-create-for-next-day-item-responbility');
        return;
    }

    $lock = FacadesCache::lock('auto-create-item-responsibility', 3600);

    if (!$lock->get()) {
        return;
    }

    try {
        Artisan::call('app:auto-create-for-next-day-item-responbility');
    } finally {
        $lock->release();
    }
})
    ->when(function () {
        return App::environment('production')
            ? now()->format('H:i') === setting('closing_day_time')
            : true;
    })
    ->everyMinute();

Schedule::call(function () {

    if (App::environment('local')) {
        Artisan::call('app:auto-closing-day-command');
        return;
    }

    $lock = FacadesCache::lock('auto-closing-day', 3600);

    if (!$lock->get()) return;

    try {
        Artisan::call('app:auto-closing-day-command');
    } finally {
        $lock->release();
    }
})
    ->when(
        fn() => App::environment('production')
            ? now()->format('H:i') === setting('closing_day_time')
            : true
    )
    ->everyMinute();

Schedule::command('queue:work --stop-when-empty --tries=3 --timeout=120')
    ->everyMinute();

Schedule::call(function () {
    Log::info('Cron jalan: ' . now());
})->everyMinute();
