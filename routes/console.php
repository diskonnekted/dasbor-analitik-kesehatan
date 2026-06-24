<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('opendata:sync-kesehatan')->dailyAt('02:00')->runInBackground()->emailOutputTo('admin@banjarnegarakab.go.id');
Schedule::command('opendata:sync-kesehatan')->weeklyOn(1, '03:00')->runInBackground();
