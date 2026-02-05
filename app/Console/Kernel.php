<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Send email reminders daily at 9 AM
        $schedule->command('emails:send-reminders')
                 ->dailyAt('09:00')
                 ->timezone('Asia/Jakarta')
                 ->description('Send automatic email reminders for pending problems');

        // Process email queue every 5 minutes
        $schedule->command('emails:process-queue')
                 ->everyFiveMinutes()
                 ->description('Process the email queue');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
