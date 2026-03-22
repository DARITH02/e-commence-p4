<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class TelegramBackup extends Command
{
    protected $signature = 'telegram:backup';
    protected $description = 'Backup database and send to Telegram';

    public function handle(TelegramService $telegram)
    {
        $this->info('Starting database backup...');
        
        $connection = config('database.default');
        $filename = 'backup-' . date('Y-m-d-H-i-s') . ($connection === 'sqlite' ? '.sqlite' : '.sql');
        $path = storage_path('app/' . $filename);

        try {
            if ($connection === 'mysql') {
                $command = sprintf(
                    'mysqldump --user=%s --password=%s --host=%s --skip-ssl %s > %s',
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    config('database.connections.mysql.host'),
                    config('database.connections.mysql.database'),
                    escapeshellarg($path)
                );
            } elseif ($connection === 'pgsql') {
                putenv('PGPASSWORD=' . config('database.connections.pgsql.password'));
                $command = sprintf(
                    'pg_dump -U %s -h %s -p %s %s > %s',
                    config('database.connections.pgsql.username'),
                    config('database.connections.pgsql.host'),
                    config('database.connections.pgsql.port'),
                    config('database.connections.pgsql.database'),
                    escapeshellarg($path)
                );
            } elseif ($connection === 'sqlite') {
                $dbRelativePath = config('database.connections.sqlite.database');
                // Check if it's absolute
                if (file_exists($dbRelativePath)) {
                    $dbPath = $dbRelativePath;
                } else {
                    $dbPath = base_path($dbRelativePath);
                }

                if (file_exists($dbPath)) {
                    copy($dbPath, $path);
                    $command = null;
                } else {
                    throw new \Exception("SQLite database not found at {$dbPath}");
                }
            } else {
                $this->error("Unsupported database connection: {$connection}");
                return 1;
            }

            if ($command) {
                exec($command . ' 2>&1', $output, $returnVar);
                if ($returnVar !== 0) {
                    $errorMsg = implode("\n", $output);
                    throw new \Exception("Backup command failed (Exit {$returnVar}): {$errorMsg}");
                }
            }

            if (!file_exists($path)) {
                throw new \Exception("Backup file was not created at {$path}");
            }

            $this->info('Sending to Telegram...');
            $ok = $telegram->sendDocument($path, "🍱 Daily Database Backup\n📅 " . date('Y-m-d H:i:s'));

            if ($ok) {
                $this->info('Backup sent successfully!');
                unlink($path); // Delete local temp file
            } else {
                $this->error('Failed to send backup to Telegram.');
            }

        } catch (\Exception $e) {
            $this->error('Backup Error: ' . $e->getMessage());
            Log::error('Backup Error: ' . $e->getMessage());
            if (file_exists($path)) unlink($path);
            return 1;
        }

        return 0;
    }
}
