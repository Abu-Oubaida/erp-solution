<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShareArchive extends Mailable
{
    use Queueable, SerializesModels;
    public $shareLink;
    public $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shareLink, $message)
    {
        $this->shareLink = $shareLink;
        $this->message = $message;
    }

    public function build()
    {
        $link = $this->shareLink;
        $content = $this->message;
        return $this->subject('Share Archive with you, from '.config('app.name'))
            ->view('email/share_archive')
            ->with([
                'link' => $link,
                'content' => $content,
            ]);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
//    public function envelope()
//    {
//        return new Envelope(
//            subject: 'Share Archive',
//        );
//    }
//
//    /**
//     * Get the message content definition.
//     *
//     * @return \Illuminate\Mail\Mailables\Content
//     */
//    public function content()
//    {
//        return new Content(
//            view: 'view.name',
//        );
//    }
//
//    /**
//     * Get the attachments for the message.
//     *
//     * @return array
//     */
//    public function attachments()
//    {
//        return [];
//    }
}
