<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupService
{
    protected $backupPath;
    protected $databaseName;
    protected $databaseUser;
    protected $databasePassword;
    protected $databaseHost;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->databaseName = config('database.connections.mysql.database');
        $this->databaseUser = config('database.connections.mysql.username');
        $this->databasePassword = config('database.connections.mysql.password');
        $this->databaseHost = config('database.connections.mysql.host');

        // Ensure backup directory exists
        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    /**
     * Create a full database backup
     */
    public function createBackup($type = 'daily')
    {
        try {
            $timestamp = Carbon::now()->format('Y_m_d_His');
            $fileName = "{$this->databaseName}_{$type}_{$timestamp}.sql";
            $filePath = "{$this->backupPath}/{$fileName}";

            // Create backup using mysqldump
            $command = sprintf(
                'mysqldump -h%s -u%s -p%s %s > %s',
                $this->databaseHost,
                $this->databaseUser,
                $this->databasePassword,
                $this->databaseName,
                $filePath
            );

            $process = Process::fromShellCommandline($command);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Compress the backup
            $compressedFile = $this->compressBackup($filePath);

            // Calculate file size
            $fileSize = filesize($compressedFile);

            // Log backup creation
            DB::table('backup_logs')->insert([
                'file_name' => basename($compressedFile),
                'file_path' => $compressedFile,
                'file_size' => $fileSize,
                'backup_type' => $type,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Clean old backups based on type
            $this->cleanOldBackups($type);

            return [
                'status' => 'success',
                'file_name' => basename($compressedFile),
                'file_path' => $compressedFile,
                'file_size' => $fileSize,
                'file_size_mb' => round($fileSize / 1024 / 1024, 2)
            ];

        } catch (\Exception $e) {
            // Log failed backup
            DB::table('backup_logs')->insert([
                'file_name' => $fileName ?? 'failed_backup',
                'backup_type' => $type,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Compress backup file using gzip
     */
    protected function compressBackup($filePath)
    {
        $compressedPath = $filePath . '.gz';

        $command = "gzip -c {$filePath} > {$compressedPath}";
        $process = Process::fromShellCommandline($command);
        $process->run();

        if ($process->isSuccessful()) {
            // Delete original uncompressed file
            File::delete($filePath);
            return $compressedPath;
        }

        return $filePath; // Return original if compression fails
    }

    /**
     * Restore database from backup
     */
    public function restoreBackup($fileName)
    {
        try {
            $filePath = "{$this->backupPath}/{$fileName}";

            if (!File::exists($filePath)) {
                throw new \Exception("Backup file not found: {$fileName}");
            }

            // Decompress if needed
            $tempFile = $filePath;
            if (str_ends_with($fileName, '.gz')) {
                $tempFile = sys_get_temp_dir() . '/' . str_replace('.gz', '', $fileName);
                $command = "gunzip -c {$filePath} > {$tempFile}";
                $process = Process::fromShellCommandline($command);
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new \Exception("Failed to decompress backup file");
                }
            }

            // Restore database
            $command = sprintf(
                'mysql -h%s -u%s -p%s %s < %s',
                $this->databaseHost,
                $this->databaseUser,
                $this->databasePassword,
                $this->databaseName,
                $tempFile
            );

            $process = Process::fromShellCommandline($command);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Clean up temp file
            if ($tempFile !== $filePath) {
                File::delete($tempFile);
            }

            // Log restore operation
            DB::table('backup_logs')->insert([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'backup_type' => 'restore',
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return [
                'status' => 'success',
                'message' => 'Database restored successfully'
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get list of available backups
     */
    public function getBackupList()
    {
        $files = File::files($this->backupPath);

        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'file_name' => $file->getFilename(),
                'file_size' => $file->getSize(),
                'file_size_mb' => round($file->getSize() / 1024 / 1024, 2),
                'created_at' => Carbon::createFromTimestamp($file->getCTime()),
                'type' => $this->extractBackupType($file->getFilename())
            ];
        }

        // Sort by creation date (newest first)
        usort($backups, function($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return $backups;
    }

    /**
     * Get backup statistics
     */
    public function getBackupStats()
    {
        $backups = $this->getBackupList();

        $totalSize = array_sum(array_column($backups, 'file_size'));
        $dailyCount = count(array_filter($backups, fn($b) => $b['type'] === 'daily'));
        $weeklyCount = count(array_filter($backups, fn($b) => $b['type'] === 'weekly'));
        $monthlyCount = count(array_filter($backups, fn($b) => $b['type'] === 'monthly'));

        return [
            'total_backups' => count($backups),
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            'daily_backups' => $dailyCount,
            'weekly_backups' => $weeklyCount,
            'monthly_backups' => $monthlyCount,
            'latest_backup' => $backups[0] ?? null,
            'oldest_backup' => end($backups) ?: null
        ];
    }

    /**
     * Get backup logs from database
     */
    public function getBackupLogs($limit = 50)
    {
        return DB::table('backup_logs')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Delete specific backup
     */
    public function deleteBackup($fileName)
    {
        try {
            $filePath = "{$this->backupPath}/{$fileName}";

            if (!File::exists($filePath)) {
                throw new \Exception("Backup file not found: {$fileName}");
            }

            File::delete($filePath);

            return [
                'status' => 'success',
                'message' => 'Backup deleted successfully'
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Clean old backups based on retention policy
     */
    protected function cleanOldBackups($type)
    {
        $backups = $this->getBackupList();
        $filteredBackups = array_filter($backups, fn($b) => $b['type'] === $type);

        $retentionDays = [
            'daily' => 7,   // Keep daily backups for 7 days
            'weekly' => 30, // Keep weekly backups for 30 days
            'monthly' => 365 // Keep monthly backups for 365 days
        ];

        $cutoffDate = Carbon::now()->subDays($retentionDays[$type]);

        foreach ($filteredBackups as $backup) {
            if ($backup['created_at']->lt($cutoffDate)) {
                File::delete("{$this->backupPath}/{$backup['file_name']}");
            }
        }
    }

    /**
     * Extract backup type from filename
     */
    protected function extractBackupType($fileName)
    {
        if (str_contains($fileName, '_daily_')) {
            return 'daily';
        } elseif (str_contains($fileName, '_weekly_')) {
            return 'weekly';
        } elseif (str_contains($fileName, '_monthly_')) {
            return 'monthly';
        }
        return 'unknown';
    }

    /**
     * Create scheduled backups
     */
    public function createScheduledBackup()
    {
        $dayOfWeek = Carbon::now()->dayOfWeek;
        $dayOfMonth = Carbon::now()->day;

        // Monthly backup (1st of month)
        if ($dayOfMonth === 1) {
            return $this->createBackup('monthly');
        }

        // Weekly backup (Sunday)
        if ($dayOfWeek === Carbon::SUNDAY) {
            return $this->createBackup('weekly');
        }

        // Daily backup
        return $this->createBackup('daily');
    }

    /**
     * Test backup integrity
     */
    public function testBackupIntegrity($fileName)
    {
        try {
            $filePath = "{$this->backupPath}/{$fileName}";

            if (!File::exists($filePath)) {
                throw new \Exception("Backup file not found");
            }

            // Test decompression if compressed
            if (str_ends_with($fileName, '.gz')) {
                $command = "gunzip -t {$filePath}";
                $process = Process::fromShellCommandline($command);
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new \Exception("Backup file is corrupted");
                }
            }

            // Check file size is reasonable (> 1MB)
            $fileSize = File::size($filePath);
            if ($fileSize < 1024 * 1024) {
                throw new \Exception("Backup file seems too small");
            }

            return [
                'status' => 'success',
                'message' => 'Backup integrity test passed'
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get backup storage location
     */
    public function getBackupPath()
    {
        return $this->backupPath;
    }

    /**
     * Calculate next scheduled backup time
     */
    public function getNextBackupTime()
    {
        $now = Carbon::now();
        $nextDaily = $now->copy()->addDay()->startOfDay()->addHours(2); // 2 AM

        return [
            'next_daily' => $nextDaily->toIso8601String(),
            'next_weekly' => $now->next(Carbon::SUNDAY)->startOfDay()->addHours(2)->toIso8601String(),
            'next_monthly' => $now->copy()->addMonth()->startOfMonth()->addHours(2)->toIso8601String()
        ];
    }
}