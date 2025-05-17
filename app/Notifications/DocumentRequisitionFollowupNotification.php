<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentRequisitionFollowupNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subject,$user,$message,$company,$notification,$email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subject,$user,$message,$company,$notify, $email)
    {
        $this->subject = $subject;
        $this->user = $user;
        $this->message = $message;
        $this->company = $company;
        $this->notification = $notify;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $notificationTypes = [];

        if ($this->notification !== null) {
            $notificationTypes[] = 'database';
        }

        if ($this->email !== null) {
            $notificationTypes[] = 'mail'; // use 'mail' not 'email'
        }

        if (!empty($notificationTypes)) {
            return $notificationTypes;
        }

        throw new \Exception('Notification type not defined');
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->email != null) {
            return (new MailMessage)
                ->subject($this->subject)
                ->view('back-end.requisition.emails.custom_reminder', [
                    'user' => $this->user,
                    'body' => $this->message,
                    'company' => $this->company,
                ]);
        }
        return null;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if ($this->notification != null)
        {
            return [
                'title' => $this->subject,
                'greeting' => "Hello {$this->user->name},",
                'body' => array_merge([
                    $this->message
                ], [
                    "Thank you!",
                ]),
                'action_text' => null,
                'action_url' => null,
                'footer' => "Regards,<br>{$this->company->company_name} | " . env('APP_NAME') . " Team",
            ];
        }
    }

    public function toDatabase($notifiable)
    {
        if ($this->notification != null)
            return $this->toArray($notifiable);
        return null;
    }
}
