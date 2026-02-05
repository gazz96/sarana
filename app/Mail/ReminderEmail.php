<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $title;
    public $message;
    public $actionUrl;
    public $actionText;
    public $additionalInfo;

    public function __construct(string $title, string $message, string $actionUrl = null, string $actionText = null, string $additionalInfo = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
        $this->additionalInfo = $additionalInfo;
    }

    public function build()
    {
        return $this->view('emails.reminder')
            ->subject('⏰ Pengingat: ' . $this->title)
            ->with([
                'title' => $this->title,
                'message' => $this->message,
                'action_url' => $this->actionUrl,
                'action_text' => $this->actionText,
                'additional_info' => $this->additionalInfo,
            ]);
    }
}