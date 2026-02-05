<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {type=daily : Backup type (daily, weekly, monthly)}';
    protected $description = 'Create database backup with automatic scheduling';

    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    public function handle()
    {
        $type = $this->argument('type');

        $this->info("Starting {$type} database backup...");

        try {
            $result = $this->backupService->createBackup($type);

            if ($result['status'] === 'success') {
                $this->info("✅ Backup created successfully!");
                $this->info("File: {$result['file_name']}");
                $this->info("Size: {$result['file_size_mb']} MB");

                Log::info("Database backup completed", [
                    'type' => $type,
                    'file' => $result['file_name'],
                    'size' => $result['file_size_mb']
                ]);

                return Command::SUCCESS;
            } else {
                $this->error("❌ Backup failed: {$result['message']}");
                
                Log::error("Database backup failed", [
                    'type' => $type,
                    'error' => $result['message']
                ]);

                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error("❌ Backup error: {$e->getMessage()}");
            
            Log::error("Database backup exception", [
                'type' => $type,
                'error' => $e->getMessage()
            ]);

            return Command::FAILURE;
        }
    }
}