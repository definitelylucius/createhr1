<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewApplicantNotification extends Notification
{
    use Queueable;
    
    protected $applicant;

    public function __construct($applicant)
    {
        $this->applicant = $applicant;
    }

    public function via($notifiable)
    {
        return ['database']; // Store in the database
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new applicant has submitted an application.',
            'applicant_name' => $this->applicant->name ?? 'Unknown',
            'application_id' => $this->applicant->id,
            'url' => route('staff.applicants.show', ['id' => $this->applicant->id])
        ];
    }
}
