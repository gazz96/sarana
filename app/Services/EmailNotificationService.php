<?php

namespace App\Services;

use App\Jobs\SendBulkEmails;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class EmailNotificationService
{
    public function sendWorkflowNotification($users, $problem, $event, $data = [])
    {
        try {
            $data['problem_id'] = $problem->id;
            $data['event'] = $event;
            $data['event_name'] = $this->getEventName($event);

            if (config('notifications.email.queue.enabled')) {
                SendBulkEmails::dispatch($users, 'workflow', $data);
            } else {
                foreach ($users as $user) {
                    \Mail::to($user)->send(new \App\Mail\WorkflowNotificationEmail($data, $problem, $event));
                }
            }

            Log::info('Workflow notification emails queued/sent', [
                'event' => $event,
                'problem_id' => $problem->id,
                'recipients_count' => count($users),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send workflow notification emails', [
                'event' => $event,
                'problem_id' => $problem->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function sendReminder($users, string $title, string $message, string $actionUrl = null, string $actionText = null, string $additionalInfo = null)
    {
        try {
            $data = [
                'title' => $title,
                'message' => $message,
                'action_url' => $actionUrl,
                'action_text' => $actionText,
                'additional_info' => $additionalInfo,
            ];

            if (config('notifications.email.queue.enabled')) {
                SendBulkEmails::dispatch($users, 'reminder', $data);
            } else {
                foreach ($users as $user) {
                    \Mail::to($user)->send(new \App\Mail\ReminderEmail($title, $message, $actionUrl, $actionText, $additionalInfo));
                }
            }

            Log::info('Reminder emails queued/sent', [
                'title' => $title,
                'recipients_count' => count($users),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send reminder emails', [
                'title' => $title,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function sendAlert($users, string $title, string $message, string $urgent = null, string $details = null, string $actionUrl = null, string $actionText = null, string $additionalInfo = null)
    {
        try {
            $data = [
                'title' => $title,
                'message' => $message,
                'urgent' => $urgent,
                'details' => $details,
                'action_url' => $actionUrl,
                'action_text' => $actionText,
                'additional_info' => $additionalInfo,
            ];

            if (config('notifications.email.queue.enabled')) {
                SendBulkEmails::dispatch($users, 'alert', $data);
            } else {
                foreach ($users as $user) {
                    \Mail::to($user)->send(new \App\Mail\AlertEmail($title, $message, $urgent, $details, $actionUrl, $actionText, $additionalInfo));
                }
            }

            Log::info('Alert emails queued/sent', [
                'title' => $title,
                'recipients_count' => count($users),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send alert emails', [
                'title' => $title,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function sendToRole(string $role, string $emailType, array $data = [])
    {
        try {
            $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })->get();

            if ($users->isEmpty()) {
                Log::warning('No users found for role', ['role' => $role]);
                return false;
            }

            switch ($emailType) {
                case 'reminder':
                    return $this->sendReminder(
                        $users,
                        $data['title'] ?? 'Pengingat',
                        $data['message'] ?? 'Anda memiliki tugas yang perlu ditangani.',
                        $data['action_url'] ?? null,
                        $data['action_text'] ?? null,
                        $data['additional_info'] ?? null
                    );

                case 'alert':
                    return $this->sendAlert(
                        $users,
                        $data['title'] ?? 'Peringatan',
                        $data['message'] ?? 'Perhatian diperlukan.',
                        $data['urgent'] ?? null,
                        $data['details'] ?? null,
                        $data['action_url'] ?? null,
                        $data['action_text'] ?? null,
                        $data['additional_info'] ?? null
                    );

                default:
                    Log::warning('Unknown email type for role notification', ['email_type' => $emailType]);
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send notification to role', [
                'role' => $role,
                'email_type' => $emailType,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    protected function getEventName(string $event): string
    {
        $events = [
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

        return $events[$event] ?? 'Notifikasi';
    }

    public function sendPendingProblemsReminder()
    {
        try {
            $pendingProblems = \App\Models\Problem::whereIn('status', [0, 1, 2])
                ->where('updated_at', '>', now()->subDays(7))
                ->get();

            foreach ($pendingProblems as $problem) {
                $recipients = [];

                if ($problem->status === 0 || $problem->status === 1) {
                    $recipients = User::whereHas('roles', function ($query) {
                        $query->whereIn('name', ['teknisi', 'admin']);
                    })->get();

                    $this->sendReminder(
                        $recipients,
                        'Problem Menunggu Penanganan',
                        "Problem {$problem->code} masih menunggu untuk diproses. Mohon segera ditangani.",
                        route('problems.show', $problem),
                        'Lihat Problem',
                        "Problem dibuat pada: " . $problem->created_at->format('d M Y H:i')
                    );
                } elseif ($problem->status === 2) {
                    if ($problem->technician) {
                        $recipients = collect([$problem->technician]);

                        $this->sendReminder(
                            $recipients,
                            'Problem Sedang Diproses',
                            "Problem {$problem->code} sedang Anda kerjakan. Mohon segera diselesaikan.",
                            route('problems.show', $problem),
                            'Lihat Problem',
                            "Problem sudah berjalan selama: " . $problem->updated_at->diffForHumans()
                        );
                    }
                }
            }

            Log::info('Pending problems reminder emails sent', [
                'problems_checked' => $pendingProblems->count(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send pending problems reminder', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function sendOverdueProblemsAlert()
    {
        try {
            $overdueProblems = \App\Models\Problem::whereIn('status', [0, 1, 2])
                ->where('updated_at', '<', now()->subDays(14))
                ->get();

            if ($overdueProblems->isNotEmpty()) {
                $adminUsers = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->get();

                $this->sendAlert(
                    $adminUsers,
                    'Problem Overdue Terdeteksi',
                    "Terdapat {$overdueProblems->count()} problem yang sudah overdue (lebih dari 14 hari).",
                    '⚠️ PERLU PERHATIAN SEGERA',
                    "Problem dengan status overdue memerlukan tindakan segera untuk mencegah penundaan lebih lanjut.",
                    route('problems.index', ['status' => '0,1,2']),
                    'Lihat Problem Overdue',
                    "Total problem overdue: {$overdueProblems->count()}"
                );

                Log::info('Overdue problems alert sent', [
                    'overdue_count' => $overdueProblems->count(),
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send overdue problems alert', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}