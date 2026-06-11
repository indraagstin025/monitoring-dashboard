<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Cek semua target aktif setiap menit (interval per-target dikelola di dalam command)
Schedule::command('monitor:run')->everySecond();

// Hitung ulang uptime % setiap jam
Schedule::command('uptime:calculate')->hourly();

// Cek SSL expiry setiap hari jam 08.00
Schedule::command('ssl:check')->dailyAt('08:00');
