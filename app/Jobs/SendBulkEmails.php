<?php

namespace App\Jobs;

use App\Mail\AlertEmail;
use App\Mail\ReminderEmail;
use App\Mail\WorkflowNotificationEmail;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBulkEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;
    protected $emailType;
    protected $data;

    public $timeout = 300;

    public function __construct($users, string $emailType, array $data = [])
    {
        $this->users = $users;
        $this->emailType = $emailType;
        $this->data = $data;
    }

    public function handle()
    {
        $chunkSize = config('notifications.email.chunk_size', 50);
        $delayBetweenChunks = config('notifications.email.delay_between_chunks', 2);

        collect($this->users)->chunk($chunkSize)->each(function ($userChunk, $index) use ($delayBetweenChunks) {
            if ($index > 0) {
                sleep($delayBetweenChunks);
            }

            foreach ($userChunk as $user) {
                try {
                    $this->sendEmailToUser($user);
                } catch (\Exception $e) {
                    Log::error('Failed to send email to user', [
                        'user_id' => $user->id ?? 'unknown',
                        'email_type' => $this->emailType,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        Log::info('Bulk email job completed', [
            'email_type' => $this->emailType,
            'total_users' => count($this->users),
        ]);
    }

    protected function sendEmailToUser($user)
    {
        if (!isset($user->email) || filter_var($user->email, FILTER_VALIDATE_EMAIL) === false) {
            Log::warning('Invalid or missing email address', [
                'user_id' => $user->id ?? 'unknown',
                'email' => $user->email ?? 'not provided',
            ]);
            return;
        }

        switch ($this->emailType) {
            case 'workflow':
                $problem = Problem::find($this->data['problem_id'] ?? null);
                $event = $this->data['event'] ?? 'notification';
                Mail::to($user)->send(new WorkflowNotificationEmail($this->data, $problem, $event));
                break;

            case 'reminder':
                Mail::to($user)->send(new ReminderEmail(
                    $this->data['title'] ?? 'Pengingat',
                    $this->data['message'] ?? 'Anda memiliki tugas yang perlu ditangani.',
                    $this->data['action_url'] ?? null,
                    $this->data['action_text'] ?? null,
                    $this->data['additional_info'] ?? null
                ));
                break;

            case 'alert':
                Mail::to($user)->send(new AlertEmail(
                    $this->data['title'] ?? 'Peringatan',
                    $this->data['message'] ?? 'Perhatian diperlukan.',
                    $this->data['urgent'] ?? null,
                    $this->data['details'] ?? null,
                    $this->data['action_url'] ?? null,
                    $this->data['action_text'] ?? null,
                    $this->data['additional_info'] ?? null
                ));
                break;

            default:
                Log::warning('Unknown email type', [
                    'email_type' => $this->emailType,
                    'user_id' => $user->id ?? 'unknown',
                ]);
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Bulk email job failed', [
            'email_type' => $this->emailType,
            'total_users' => count($this->users),
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}