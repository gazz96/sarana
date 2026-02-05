<?php

namespace App\Console\Commands;

use App\Services\EmailNotificationService;
use Illuminate\Console\Command;
use App\Models\User;

class TestEmailNotification extends Command
{
    protected $signature = 'notifications:test {--type=workflow : Type of notification (workflow, reminder, alert)} {--to= : Email address to send test to}';
    protected $description = 'Test email notification system by sending test emails';

    protected $emailService;

    public function __construct(EmailNotificationService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    public function handle()
    {
        $type = $this->option('type');
        $toEmail = $this->option('to');

        $this->info("Testing {$type} email notification...");

        try {
            if ($toEmail) {
                $testUser = new User(['email' => $toEmail, 'name' => 'Test User']);
                $users = collect([$testUser]);
            } else {
                $users = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->limit(1)->get();

                if ($users->isEmpty()) {
                    $this->error('No admin users found and no --to email specified.');
                    return 1;
                }
            }

            switch ($type) {
                case 'workflow':
                    $problem = \App\Models\Problem::first();
                    if (!$problem) {
                        $this->error('No problems found in database.');
                        return 1;
                    }

                    $this->info("Sending workflow notification for problem: {$problem->code}");
                    $this->emailService->sendWorkflowNotification($users, $problem, 'problem_finished');
                    break;

                case 'reminder':
                    $this->info("Sending reminder email...");
                    $this->emailService->sendReminder(
                        $users,
                        'Test Reminder',
                        'This is a test reminder from SARANA system.',
                        url('/dashboard'),
                        'Go to Dashboard',
                        'Test reminder sent at: ' . now()->format('d M Y H:i')
                    );
                    break;

                case 'alert':
                    $this->info("Sending alert email...");
                    $this->emailService->sendAlert(
                        $users,
                        'Test Alert',
                        'This is a test alert from SARANA system.',
                        '⚠️ TEST ALERT',
                        'This is a test alert message to verify the email system is working correctly.',
                        url('/dashboard'),
                        'Go to Dashboard'
                    );
                    break;

                default:
                    $this->error("Unknown notification type: {$type}");
                    return 1;
            }

            $this->info("✅ Test email queued successfully!");
            $this->info("Recipient: " . $users->first()->email);
            $this->info("Type: {$type}");
            
            if (config('notifications.email.queue.enabled')) {
                $this->warn("Email is queued. Run 'php artisan emails:process-queue' to process.");
            } else {
                $this->warn("Email should be sent immediately (sync mode).");
            }

            $this->info("Check Mailhog or your email inbox to verify delivery.");
            return 0;

        } catch (\Exception $e) {
            $this->error("Failed to send test email: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}