<?php

namespace App\Console\Commands;

use App\Services\DatabaseOptimizer;
use Illuminate\Console\Command;

class CleanupDatabase extends Command
{
    protected $signature = 'db:cleanup {--days=90 : Number of days to keep data}';
    protected $description = 'Clean up old database records to maintain performance';

    protected DatabaseOptimizer $dbOptimizer;

    public function __construct(DatabaseOptimizer $dbOptimizer)
    {
        parent::__construct();
        $this->dbOptimizer = $dbOptimizer;
    }

    public function handle()
    {
        $this->info('🗑️ Starting Database Cleanup...');

        $daysToKeep = (int) $this->option('days');
        $this->line("Keeping data from the last <info>{$daysToKeep}</info> days");

        try {
            $deletedCount = $this->dbOptimizer->cleanupOldData($daysToKeep);
            
            $this->info("✓ Cleanup completed successfully");
            $this->line("Deleted <info>{$deletedCount}</info> old records");

            return 0;

        } catch (\Exception $e) {
            $this->error("✗ Cleanup failed: " . $e->getMessage());
            return 1;
        }
    }
}