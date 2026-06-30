<?php

namespace App\Jobs\Central;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExportDatabaseJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
     public function handle()
    {
        $file = storage_path('app/backup_' . now()->format('Y_m_d_H_i_s') . '.sql');

        $command = "mysqldump -u" . env('DB_USERNAME')
            . " -p" . env('DB_PASSWORD')
            . " " . env('DB_DATABASE')
            . " > " . $file;

        exec($command);
    }
}
