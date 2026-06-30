<?php

namespace App\Jobs\Central;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Exception;

class ExportDatabaseJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $defaultConnection = config('database.default');
        $driver = config("database.connections.{$defaultConnection}.driver");

        $timestamp = now()->format('Y_m_d_H_i_s');

        switch ($driver) {
            case 'sqlite':
                $this->backupSqlite($defaultConnection, $timestamp);
                break;

            case 'mysql':
            case 'mariadb':
                $this->backupMysql($defaultConnection, $timestamp);
                break;

            case 'pgsql':
                $this->backupPostgres($defaultConnection, $timestamp);
                break;

            default:
                throw new Exception("O driver de banco de dados '{$driver}' não possui uma estratégia de backup configurada.");
        }
    }

    protected function backupSqlite(string $connection, string $timestamp): void
    {
        $databasePath = config("database.connections.{$connection}.database");
        $backupPath = storage_path("app/database/backup_{$timestamp}.sqlite");

        if (!File::exists($databasePath)) {
            throw new Exception("Arquivo SQLite não encontrado em: {$databasePath}");
        }

        File::copy($databasePath, $backupPath);
    }

    protected function backupMysql(string $connection, string $timestamp): void
    {
        $backupPath = storage_path("app/database/backup_{$timestamp}.sql");
        $handle = fopen($backupPath, 'w+');

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");
        $tables = DB::connection($connection)->select('SHOW TABLES');
        $dbKey = 'Tables_in_' . config("database.connections.{$connection}.database");

        foreach ($tables as $table) {
            $tableName = $table->$dbKey;
            $createTableQuery = DB::connection($connection)->select("SHOW CREATE TABLE `{$tableName}`")[0];
            $createTableSql = $createTableQuery->{'Create Table'};

            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
            fwrite($handle, $createTableSql . ";\n\n");

            DB::connection($connection)->table($tableName)->chunk(500, function ($rows) use ($handle, $tableName) {
                foreach ($rows as $row) {
                    $values = array_map(function ($value) {
                        return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                    }, (array)$row);

                    fwrite($handle, "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n");
                }
            });
            fwrite($handle, "\n\n");
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    protected function backupPostgres(string $connection, string $timestamp): void
    {
        $config = config("database.connections.{$connection}");
        $backupPath = storage_path("app/database/backup_{$timestamp}.sql");

        // O pg_dump exige a senha via variável de ambiente do processo
        $env = ['PGPASSWORD' => $config['password']];
        
        $command = [
            'pg_dump',
            "-h", $config['host'],
            "-p", $config['port'] ?? '5432',
            "-U", $config['username'],
            "-d", $config['database'],
            "-F", "p", // Formato texto plano (.sql)
        ];

        $process = new Process($command, null, $env);
        $process->setTimeout(300);
        
        $process->run(null, [
            'STDOUT' => fopen($backupPath, 'w')
        ]);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
