<?php

namespace App\Mail;

use App\Models\Problem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkflowNotificationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $problem;
    public $event;
    public $eventName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data, Problem $problem = null, string $event = '')
    {
        $this->data = $data;
        $this->problem = $problem;
        $this->event = $event;
        $this->eventName = $data['event_name'] ?? 'Notification';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->getSubject();
        $view = $this->getView();
        
        return $this->view($view)
            ->subject($subject)
            ->with([
                'data' => $this->data,
                'problem' => $this->problem,
                'event' => $this->event,
                'eventName' => $this->eventName,
            ]);
    }

    /**
     * Get email subject based on event type
     */
    protected function getSubject(): string
    {
        switch ($this->event) {
            case 'problem_created':
                return "🔧 Problem Baru: {$this->problem->code}";
            case 'problem_submitted':
                return "📤 Problem Diajukan: {$this->problem->code}";
            case 'problem_accepted':
                return "✅ Problem Diterima: {$this->problem->code}";
            case 'problem_in_progress':
                return "🔧 Problem Sedang Dikerjakan: {$this->problem->code}";
            case 'problem_finished':
                return "🎉 Problem Selesai: {$this->problem->code}";
            case 'problem_approved_management':
                return "🛡️ Problem Disetujui Management: {$this->problem->code}";
            case 'problem_approved_admin':
                return "👤 Problem Disetujui Admin: {$this->problem->code}";
            case 'problem_approved_finance':
                return "💳 Problem Disetujui Keuangan: {$this->problem->code}";
            case 'problem_cancelled':
                return "❌ Problem Dibatalkan: {$this->problem->code}";
            default:
                return "🔔 Notifikasi SARANA: {$this->eventName}";
        }
    }

    /**
     * Get email view based on event type
     */
    protected function getView(): string
    {
        switch ($this->event) {
            case 'problem_approved_finance':
                return 'emails.workflow.completed';
            default:
                return 'emails.workflow.default';
        }
    }
}
