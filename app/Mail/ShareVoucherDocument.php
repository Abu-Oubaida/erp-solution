<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShareVoucherDocument extends Mailable implements ShouldQueue
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
        return $this->subject('Share Archive Document with you, from '.config('app.name'))
            ->view('email/share_voucher_document')
            ->with([
                'link' => $link,
                'content' => $content,
            ]);
    }

//    /**
//     * Get the message envelope.
//     *
//     * @return \Illuminate\Mail\Mailables\Envelope
//     */
//    public function envelope()
//    {
//        return new Envelope(
//            subject: 'Share Voucher Document with you from'.config('app.name'),
//        );
//    }
//
//    /**
//     * Get the message content definition.
//     *
//     * @return Application|Factory|View
//     */
//    public function content()
//    {
//        $link = $this->shareLink;
//        $content = $this->message;
//        return view('email/share_voucher_document',compact('link','content'));
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
