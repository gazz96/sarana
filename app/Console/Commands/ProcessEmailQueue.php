<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessEmailQueue extends Command
{
    protected $signature = 'emails:process-queue {--tries=3 : Number of times to attempt a job}';
    protected $description = 'Process the email queue';

    public function handle()
    {
        $this->info('Processing email queue...');

        try {
            $tries = $this->option('tries');
            
            $this->info("Starting queue worker with {$tries} tries...");
            
            $exitCode = \Artisan::call("queue:work", [
                '--once' => true,
                '--tries' => $tries,
                '--timeout' => 300,
            ]);

            if ($exitCode === 0) {
                $this->info('Email queue processed successfully.');
                Log::info('Email queue processed successfully');
            } else {
                $this->warn('Queue worker exited with code: ' . $exitCode);
                Log::warning('Queue worker exited with non-zero code', ['exit_code' => $exitCode]);
            }

            return $exitCode;
        } catch (\Exception $e) {
            $this->error('Failed to process email queue: ' . $e->getMessage());
            Log::error('Email queue processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }
}