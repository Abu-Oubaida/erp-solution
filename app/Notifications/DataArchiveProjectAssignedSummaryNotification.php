<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DataArchiveProjectAssignedSummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $projectName;
    public $dataTypes; // array of ['name' => ..., 'deadline' => ...]
    public $action_url;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($projectName, array $dataTypes,$action_url = null)
    {
        $this->projectName = $projectName;
        $this->dataTypes = $dataTypes;
        $this->action_url = $action_url;
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
            ->subject("You are assigned to upload document(s) of project: {$this->projectName}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been assigned to upload the following data type(s) under project:  **{$this->projectName}** in online document archiving system" );

        foreach ($this->dataTypes as $dataType) {
            $mail->line("- {$dataType['name']} (Deadline: " . \Carbon\Carbon::parse($dataType['deadline'])->format('d M Y') . ")");
        }

        return $mail
            ->action('View Task', $this->action_url)
            ->line('Please upload the required documents properly before the respective deadlines.')
            ->line("**{N:B: This is an automated system-generated email. Hence, no need to reply.}**")
            ->line('Thank you!');

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "You are assigned to upload document(s) of project: {$this->projectName}",
            'data_types' => array_map(function ($dt) {
                return "{$dt['name']} (Deadline: " . \Carbon\Carbon::parse($dt['deadline'])->format('d M Y') . ")";
            }, $this->dataTypes),
            'url' => $this->action_url,
            'next_url' => env('APP_URL') . '/archive-data-upload',
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "You are assigned to upload document(s) of project: **{$this->projectName}**",
            'data_types' => array_map(function ($dt) {
                return "{$dt['name']} (Deadline: " . \Carbon\Carbon::parse($dt['deadline'])->format('d M Y') . ")";
            }, $this->dataTypes),
            'url' => $this->action_url,
            'next_url' => env('APP_URL') . '/archive-data-upload',
        ];
    }
}
