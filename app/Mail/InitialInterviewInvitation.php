<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\JobApplication;
use App\Models\User;

class InitialInterviewInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $interviewDetails;

    public function __construct(JobApplication $application, array $interviewDetails)
    {
        $this->application = $application;
        $this->interviewDetails = $interviewDetails;
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.initial_interview',
            with: [
                'application' => $this->application,
                'details' => $this->interviewDetails
            ]
        );
    }
}