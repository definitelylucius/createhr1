<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\JobApplication;

class ApplicantRecommendedNotification extends Notification
{
    use Queueable;

    protected $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail']; // Ensure you're using the correct channels
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Applicant Recommended for Hiring')
            ->line('An applicant has been recommended for hiring.')
            ->action('View Application', url('/admin/applicants/' . $this->application->id))
            ->line('Please review the application.');
    }
}
