<?php

namespace App\Services;

use App\Models\User;
use App\Models\Problem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    protected array $workflowEvents = [
        'problem_created' => 'Problem Baru Dibuat',
        'problem_submitted' => 'Problem Diajukan',
        'problem_accepted' => 'Problem Diterima Teknisi',
        'problem_in_progress' => 'Problem Sedang Dikerjakan',
        'problem_finished' => 'Problem Selesai Dikerjakan',
        'problem_approved_management' => 'Problem Disetujui Management',
        'problem_approved_admin' => 'Problem Disetujui Admin',
        'problem_approved_finance' => 'Problem Disetujui Keuangan',
        'problem_cancelled' => 'Problem Dibatalkan',
    ];

    public function notifyWorkflowChange(Problem $problem, string $event, array $data = []): void
    {
        try {
            $recipients = $this->getRecipientsForEvent($problem, $event);
            
            foreach ($recipients as $recipient) {
                if ($this->shouldNotifyUser($recipient, $event)) {
                    $this->sendNotification($recipient, $problem, $event, $data);
                }
            }
        } catch (\Exception $e) {
            Log::error('Notification failed: ' . $e->getMessage(), [
                'event' => $event,
                'problem_id' => $problem->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    protected function getRecipientsForEvent(Problem $problem, string $event): array
    {
        $recipients = collect();

        switch ($event) {
            case 'problem_created':
            case 'problem_submitted':
                // Notify technicians and admin
                $recipients = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['teknisi', 'admin']);
                })->get();
                break;

            case 'problem_accepted':
            case 'problem_in_progress':
            case 'problem_finished':
                // Notify the reporter (guru) and admin
                $recipients = collect([$problem->user])->filter();
                $adminUsers = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->get();
                $recipients = $recipients->merge($adminUsers);
                break;

            case 'problem_approved_management':
                // Notify admin and finance
                $recipients = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['admin', 'keuangan']);
                })->get();
                break;

            case 'problem_approved_admin':
                // Notify finance and management
                $recipients = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['keuangan', 'lembaga']);
                })->get();
                break;

            case 'problem_approved_finance':
                // Notify all parties that process is complete
                $recipients = collect([
                    $problem->user,
                    $problem->technician,
                    $problem->admin,
                    $problem->management,
                    $problem->finance
                ])->filter();
                break;

            case 'problem_cancelled':
                // Notify everyone involved
                $recipients = collect([
                    $problem->user,
                    $problem->technician,
                    $problem->admin,
                ])->filter();
                break;
        }

        return $recipients->all();
    }

    protected function shouldNotifyUser(User $user, string $event): bool
    {
        $preferences = $user->notificationPreferences;
        
        if (!$preferences) {
            return true; // Default to true if no preferences set
        }

        return $preferences->in_app_enabled ?? true;
    }

    protected function sendNotification(User $user, Problem $problem, string $event, array $data): void
    {
        $notificationData = array_merge([
            'event' => $event,
            'event_name' => $this->workflowEvents[$event] ?? $event,
            'problem_id' => $problem->id,
            'problem_code' => $problem->code,
            'problem_issue' => $problem->issue,
            'problem_status' => $problem->status,
            'timestamp' => now()->toDateTimeString(),
        ], $data);

        // Store in-app notification
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\\Notifications\\WorkflowNotification',
            'data' => json_encode($notificationData),
            'read_at' => null,
        ]);

        // Send email if enabled
        if ($this->shouldSendEmail($user, $event)) {
            $this->sendEmailNotification($user, $notificationData);
        }
    }

    protected function shouldSendEmail(User $user, string $event): bool
    {
        $preferences = $user->notificationPreferences;
        
        if (!$preferences) {
            return config('notifications.email.enabled', true);
        }

        return ($preferences->email_enabled ?? true) && config('notifications.email.enabled', true);
    }

    protected function sendEmailNotification(User $user, array $data): void
    {
        try {
            $problem = null;
            if (isset($data['problem_id'])) {
                $problem = Problem::find($data['problem_id']);
            }

            $event = $data['event'] ?? 'notification';
            
            // Send email using WorkflowNotificationEmail
            Mail::to($user)->send(new \App\Mail\WorkflowNotificationEmail($data, $problem, $event));
            
            Log::info('Email notification sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'event' => $event,
                'problem_id' => $data['problem_id'] ?? null
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function getUnreadNotifications(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $user->unreadNotifications()->latest()->limit(20)->get();
    }

    public function markAsRead(User $user, string $notificationId): bool
    {
        try {
            $notification = $user->notifications()->findOrFail($notificationId);
            $notification->markAsRead();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read', [
                'user_id' => $user->id,
                'notification_id' => $notificationId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function markAllAsRead(User $user): bool
    {
        try {
            $user->unreadNotifications->markAsRead();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to mark all notifications as read', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}