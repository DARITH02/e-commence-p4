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
            $binaryWorked = false;
            $command = null;

            if ($connection === 'mysql') {
                $hasMysqlDump = !empty(shell_exec('which mysqldump'));
                if ($hasMysqlDump) {
                    $command = sprintf(
                        'mysqldump --user=%s --password=%s --host=%s --skip-ssl %s > %s',
                        config('database.connections.mysql.username'),
                        config('database.connections.mysql.password'),
                        config('database.connections.mysql.host'),
                        config('database.connections.mysql.database'),
                        escapeshellarg($path)
                    );
                }
            } elseif ($connection === 'pgsql') {
                $hasPgDump = !empty(shell_exec('which pg_dump'));
                if ($hasPgDump) {
                    putenv('PGPASSWORD=' . config('database.connections.pgsql.password'));
                    $command = sprintf(
                        'pg_dump -U %s -h %s -p %s %s > %s',
                        config('database.connections.pgsql.username'),
                        config('database.connections.pgsql.host'),
                        config('database.connections.pgsql.port'),
                        config('database.connections.pgsql.database'),
                        escapeshellarg($path)
                    );
                }
            } elseif ($connection === 'sqlite') {
                $dbRelativePath = config('database.connections.sqlite.database');
                $dbPath = file_exists($dbRelativePath) ? $dbRelativePath : base_path($dbRelativePath);
                
                if (file_exists($dbPath)) {
                    copy($dbPath, $path);
                    $binaryWorked = true;
                }
            }

            if ($command) {
                exec($command . ' 2>&1', $output, $returnVar);
                if ($returnVar === 0) {
                    $binaryWorked = true;
                }
            }

            // Fallback: Pure PHP Dumper
            if (!$binaryWorked) {
                if ($connection === 'mysql') {
                    $this->info('Binary dump failed or not found. Using MySQL PHP fallback...');
                    $this->phpDumpMysql($path);
                    $binaryWorked = true;
                } elseif ($connection === 'pgsql') {
                    $this->info('Binary dump failed or not found. Using PostgreSQL PHP fallback...');
                    $this->phpDumpPgsql($path);
                    $binaryWorked = true;
                }
            }

            if (!$binaryWorked) {
                throw new \Exception("Backup failed: Binary not found and no fallback implemented for {$connection}");
            }

            if (!file_exists($path)) {
                throw new \Exception("Backup file was not created at {$path}");
            }

            $this->info('Sending to Telegram...');
            $ok = $telegram->sendDocument($path, "🍱 Daily Database Backup\n📅 " . date('Y-m-d H:i:s'));

            if ($ok) {
                $this->info('Backup sent successfully!');
                unlink($path); 
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

    private function phpDumpMysql($path)
    {
        $db = \Illuminate\Support\Facades\DB::connection()->getPdo();
        $tables = [];
        $result = $db->query('SHOW TABLES');
        while ($row = $result->fetch(\PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        $sql = "-- EcommercePro PHP Backup Fallback (MySQL)\n";
        $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            $res = $db->query("SHOW CREATE TABLE `{$table}`");
            $row = $res->fetch(\PDO::FETCH_NUM);
            $sql .= "\n\n" . $row[1] . ";\n\n";

            $res = $db->query("SELECT * FROM `{$table}`");
            while ($row = $res->fetch(\PDO::FETCH_NUM)) {
                $sql .= "INSERT INTO `{$table}` VALUES(";
                for ($j = 0; $j < count($row); $j++) {
                    if (!isset($row[$j])) {
                        $sql .= 'NULL';
                    } else {
                        $val = str_replace(["\\", "\n", "\r", "'", '"'], ["\\\\", "\\n", "\\r", "\'", '\"'], $row[$j]);
                        $sql .= "'" . $val . "'";
                    }
                    if ($j < (count($row) - 1)) {
                        $sql .= ',';
                    }
                }
                $sql .= ");\n";
            }
        }
        file_put_contents($path, $sql);
    }

    private function phpDumpPgsql($path)
    {
        $db = \Illuminate\Support\Facades\DB::connection()->getPdo();
        $tables = [];
        $result = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
        while ($row = $result->fetch(\PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        $sql = "-- EcommercePro PHP Backup Fallback (PostgreSQL)\n";
        $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            // Simplified CREATE TABLE script
            $sql .= "\n\n-- Dumping data for table {$table}\n";
            $res = $db->query("SELECT * FROM \"{$table}\"");
            while ($row = $res->fetch(\PDO::FETCH_NUM)) {
                $sql .= "INSERT INTO \"{$table}\" VALUES (";
                for ($j = 0; $j < count($row); $j++) {
                    if (!isset($row[$j])) {
                        $sql .= 'NULL';
                    } else {
                        $val = str_replace(["\\", "'"], ["\\\\", "''"], $row[$j]);
                        $sql .= "'" . $val . "'";
                    }
                    if ($j < (count($row) - 1)) {
                        $sql .= ', ';
                    }
                }
                $sql .= ");\n";
            }
        }
        file_put_contents($path, $sql);
    }
}
