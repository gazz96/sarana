<?php

namespace App\Console\Commands;

use App\Services\EmailNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEmailReminders extends Command
{
    protected $signature = 'emails:send-reminders';
    protected $description = 'Send automatic email reminders for pending problems';

    protected $emailService;

    public function __construct(EmailNotificationService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    public function handle()
    {
        $this->info('Sending email reminders...');

        try {
            $this->emailService->sendPendingProblemsReminder();
            $this->info('Pending problem reminders sent successfully.');

            $this->emailService->sendOverdueProblemsAlert();
            $this->info('Overdue problem alerts sent successfully.');

            Log::info('Email reminders command completed successfully');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email reminders: ' . $e->getMessage());
            Log::error('Email reminders command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }
}