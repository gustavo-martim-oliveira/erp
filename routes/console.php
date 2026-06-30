<?php

use App\Jobs\Central\ExportDatabaseJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(ExportDatabaseJob::class)
        ->dailyAt('00:00')
        ->when(function () {
            return \App\Models\SystemSetting::where('key', 'database_backup_enabled')->where('value', true)->exists();
        });
