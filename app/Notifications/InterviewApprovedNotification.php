<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InterviewApprovedNotification extends Notification
{
    use Queueable;

    protected $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Store in database & send email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Interview to Initiate')
            ->greeting('Hello, ' . $notifiable->name)
            ->line('An application has been approved for an interview.')
            ->action('View Application', url('/staff/interviews'))
            ->line('Please proceed with the final interview.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'An application has been approved for an interview.',
            'applicant_name' => $this->application->applicant->name,
            'application_id' => $this->application->id
        ];
    }
}

