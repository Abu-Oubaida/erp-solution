<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class DataArchiveProjectAssignedSummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $projectName;
    public $dataTypes; // array of ['name' => ..., 'deadline' => ...]
    public $action_url;
    protected $companyName;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($projectName, array $dataTypes,$companyName,$action_url = null)
    {
        $this->projectName = $projectName;
        $this->dataTypes = $dataTypes;
        $this->action_url = $action_url;
        $this->companyName = $companyName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject("Document Upload Task Assigned - {$this->projectName}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Good Day!")
            ->line("You have been assigned to upload the following data type(s) under project:  **{$this->projectName}** in online document archiving system" );

        foreach ($this->dataTypes as $dataType) {
            $mail->line("- {$dataType['name']} (Deadline: " . \Carbon\Carbon::parse($dataType['deadline'])->format('d M Y') . ")");
        }

        return $mail
            ->action('View Task', $this->action_url)
            ->line('Please upload the required documents properly before the respective deadlines.')
            ->line("**N:B: This is an automated system-generated email. Hence, no need to reply.**")
            ->line('Thank you!')
            ->salutation("Regards,\n{$this->companyName} | ".env('APP_NAME') . " Team");

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $dataLines = [];

        foreach ($this->dataTypes as $dataType) {
            $dataLines[] = "<li> {$dataType['name']} (Deadline: " . \Carbon\Carbon::parse($dataType['deadline'])->format('d M Y') . ")</li>";
        }

        return [
            'title' => "Document Upload Task Assigned - $this->projectName",
            'greeting' => "Hello {$notifiable->name},",
            'body' => array_merge([
                "Good Day!",
                "You have been assigned to upload the following data type(s) under project:  <strong>$this->projectName</strong> in online document archiving system",
            ], $dataLines, [
                "Please upload the required documents properly before the respective deadlines.",
                "Thank you!",
            ]),
            'action_text' => 'Upload Now',
            'action_url' => $this->action_url,
            'footer' => "Regards,<br>{$this->companyName} | " . env('APP_NAME') . " Team",
        ];
    }

    public function toDatabase($notifiable)
    {
        return $this->toArray($notifiable);
    }
}
