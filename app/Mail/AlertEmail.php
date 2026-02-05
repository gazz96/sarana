<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $title;
    public $message;
    public $urgent;
    public $details;
    public $actionUrl;
    public $actionText;
    public $additionalInfo;

    public function __construct(string $title, string $message, string $urgent = null, string $details = null, string $actionUrl = null, string $actionText = null, string $additionalInfo = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->urgent = $urgent;
        $this->details = $details;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
        $this->additionalInfo = $additionalInfo;
    }

    public function build()
    {
        return $this->view('emails.alert')
            ->subject('🚨 Peringatan: ' . $this->title)
            ->with([
                'title' => $this->title,
                'message' => $this->message,
                'urgent' => $this->urgent,
                'details' => $this->details,
                'action_url' => $this->actionUrl,
                'action_text' => $this->actionText,
                'additional_info' => $this->additionalInfo,
            ]);
    }
}