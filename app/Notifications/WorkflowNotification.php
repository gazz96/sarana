<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

class WorkflowNotification extends Notification
{
    use Queueable;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable): array
    {
        $channels = ['database'];
        
        // Add mail channel if email notifications are enabled
        if (config('notifications.email.enabled', true)) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        $subject = match($this->data['event']) {
            'problem_created' => 'Problem Baru Dibuat',
            'problem_submitted' => 'Problem Diajukan',
            'problem_accepted' => 'Problem Diterika',
            'problem_finished' => 'Problem Selesai',
            'problem_cancelled' => 'Problem Dibatalkan',
            default => 'Update Status Problem'
        };

        return (new MailMessage)
            ->subject($subject . ' - ' . $this->data['problem_code'])
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($this->getMessage())
            ->action('Lihat Problem', url('/problems/' . $this->data['problem_id']))
            ->line('Terima kasih telah menggunakan sistem SARPRAS.');
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'event' => $this->data['event'],
            'event_name' => $this->data['event_name'],
            'message' => $this->getMessage(),
            'problem_id' => $this->data['problem_id'],
            'problem_code' => $this->data['problem_code'],
            'problem_issue' => $this->data['problem_issue'],
            'problem_status' => $this->data['problem_status'],
            'link' => url('/problems/' . $this->data['problem_id']),
        ]);
    }

    protected function getMessage(): string
    {
        return match($this->data['event']) {
            'problem_created' => "Problem baru {$this->data['problem_code']} telah dibuat.",
            'problem_submitted' => "Problem {$this->data['problem_code']} telah diajukan dan menunggu penanganan teknisi.",
            'problem_accepted' => "Problem {$this->data['problem_code']} telah diterima oleh teknisi.",
            'problem_in_progress' => "Problem {$this->data['problem_code']} sedang dalam proses pengerjaan.",
            'problem_finished' => "Problem {$this->data['problem_code']} telah selesai dikerjakan dan menunggu persetujuan.",
            'problem_approved_management' => "Problem {$this->data['problem_code']} telah disetujui oleh management.",
            'problem_approved_admin' => "Problem {$this->data['problem_code']} telah disetujui oleh admin.",
            'problem_approved_finance' => "Problem {$this->data['problem_code']} telah disetujui oleh keuangan dan selesai.",
            'problem_cancelled' => "Problem {$this->data['problem_code']} telah dibatalkan.",
            default => "Update status problem {$this->data['problem_code']}."
        };
    }

    public function toArray($notifiable): array
    {
        return $this->data;
    }
}