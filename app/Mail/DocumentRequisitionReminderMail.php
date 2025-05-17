<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentRequisitionReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $message, $company;

    public function __construct($subject, $user, $message, $company)
    {
        $this->subject($subject);
        $this->user = $user;
        $this->message = $message;
        $this->company = $company;
    }

    public function build()
    {
        return $this->view('back-end.requisition.emails.custom_reminder')
            ->with([
                'user' => $this->user,
                'message' => $this->message,
                'company' => $this->company,
            ]);
    }
}
