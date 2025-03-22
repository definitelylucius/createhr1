<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\JobApplication;

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\JobApplication;

class InterviewInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $subjectLine;
    public $customMessage;

    public function __construct(JobApplication $application, $subject, $customMessage)
    {
        $this->application = $application;
        $this->subjectLine = $subject;
        $this->customMessage = $customMessage;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.interview_invitation')
                    ->with([
                        'name' => $this->application->user->name,
                        'jobTitle' => $this->application->job->title,
                        'email' => $this->application->user->email,
                        'customMessage' => $this->customMessage,
                    ]);
    }
}

