<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ApplicantHiredNotification extends Notification
{
    use Queueable;

    public $employee;

    /**
     * Create a new notification instance.
     */
    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    /**
     * Determine how the notification should be delivered.
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Saves in DB & broadcasts in real-time
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->employee->name} has been hired as an employee!",
            'url' => route('admin.recruitment.hired')
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "{$this->employee->name} has been hired as an employee!",
            'url' => route('admin.recruitment.hired')
        ]);
    }
}

