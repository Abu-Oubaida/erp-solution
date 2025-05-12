<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DataArchiveResponsibleUserAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pwdtrId;
    protected $projectName;
    protected $deadline;
    public $action_url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($pwdtrId, $projectName, $deadline,$action_url)
    {
        $this->pwdtrId = $pwdtrId;//project wise data type required id
        $this->projectName = $projectName;
        $this->deadline = $deadline;
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
        return (new MailMessage)
            ->subject('New Responsibility Assigned')
            ->line("You have been assigned to upload required data for project: {$this->projectName}.")
            ->line("Deadline: {$this->deadline}")
            ->action('View Task', $this->action_url)
            ->line('Please complete it before the deadline.');
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
            'pwdtr_id' => $this->pwdtrId,
            'project_name' => $this->projectName,
            'deadline' => $this->deadline,
            'message' => 'You have been assigned to a data upload task for project: ' . $this->projectName,
            'url' => $this->action_url,
        ];
    }
    public function toDatabase($notifiable)
    {
        return [
            'pwdtr_id' => $this->pwdtrId,
            'project_name' => $this->projectName,
            'deadline' => $this->deadline,
            'message' => 'You have been assigned to a data upload task for project: ' . $this->projectName,
            'url' => $this->action_url,
        ];
    }
}
