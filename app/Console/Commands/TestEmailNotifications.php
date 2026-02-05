<?php

namespace App\Console\Commands;

use App\Models\Problem;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email notification system by sending test emails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(NotificationService $notificationService)
    {
        $email = $this->argument('email');
        
        $this->info('Testing Email Notification System');
        $this->info('============================');
        $this->info("Target Email: {$email}");
        $this->newLine();

        // Get a test problem
        $problem = Problem::first();
        
        if (!$problem) {
            $this->error('No problems found in database. Please create a test problem first.');
            return 1;
        }

        $this->info("Using Test Problem: {$problem->code}");
        $this->newLine();

        // Test different notification types
        $notifications = [
            [
                'name' => 'Problem Submitted',
                'event' => 'problem_submitted',
                'data' => [
                    'old_status' => 0,
                    'new_status' => 1,
                    'submitter' => auth()->user()->name ?? 'System'
                ]
            ],
            [
                'name' => 'Problem Accepted',
                'event' => 'problem_accepted',
                'data' => [
                    'old_status' => 1,
                    'new_status' => 2,
                    'technician_id' => auth()->id() ?? 1
                ]
            ],
            [
                'name' => 'Problem Finished',
                'event' => 'problem_finished',
                'data' => [
                    'old_status' => 2,
                    'new_status' => 3
                ]
            ],
            [
                'name' => 'Problem Completed',
                'event' => 'problem_approved_finance',
                'data' => [
                    'approved_by' => 'Finance Team',
                    'old_status' => 3,
                    'new_status' => 3
                ]
            ]
        ];

        foreach ($notifications as $notification) {
            $this->info("Testing: {$notification['name']}");
            
            try {
                // Create test user or use existing
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => 'Test User',
                        'password' => bcrypt('password123')
                    ]
                );

                // Send notification
                $notificationService->notifyWorkflowChange(
                    $problem,
                    $notification['event'],
                    array_merge($notification['data'], [
                        'problem_id' => $problem->id,
                        'problem_code' => $problem->code,
                        'timestamp' => now()->toDateTimeString()
                    ])
                );

                $this->info("✅ {$notification['name']} - Email queued successfully");
                
                // Small delay to prevent rate limiting
                sleep(1);
                
            } catch (\Exception $e) {
                $this->error("❌ {$notification['name']} - Failed: {$e->getMessage()}");
            }
            
            $this->newLine();
        }

        // Check email queue
        $this->info('Checking email queue...');
        $queueSize = \Illuminate\Support\Facades\DB::table('jobs')->count();
        $this->info("Emails in queue: {$queueSize}");
        $this->newLine();

        $this->info('============================');
        $this->info('Test completed!');
        $this->info('Check your email inbox and spam folder.');
        $this->newLine();
        $this->info('To process queued emails, run: php artisan queue:work');
        $this->newLine();
        $this->info('To check email logs: tail storage/logs/laravel.log');

        return 0;
    }
}
