<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\JobApplication;
use App\Models\PreEmploymentDocument;

class AppointmentScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $document;

    public function __construct(JobApplication $application, PreEmploymentDocument $document)
    {
        $this->application = $application;
        $this->document = $document;
    }

    public function build()
    {
        return $this->subject('Document Verification Appointment Scheduled - ' . config('app.name'))
                   ->view('emails.appointment_scheduled');
    }
}