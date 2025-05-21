<?php

namespace App\Mail;

use App\Models\Candidate;
use App\Models\CalendarEvent;
use App\Models\HiringProcessStage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $stage;
    public $event;

    public function __construct(Candidate $candidate, HiringProcessStage $stage, CalendarEvent $event)
    {
        $this->candidate = $candidate;
        $this->stage = $stage;
        $this->event = $event;
    }

    public function build()
    {
        $subject = 'Invitation for ' . ucfirst(str_replace('_', ' ', $this->stage->stage)) . ' - ' . $this->candidate->job->title;

        return $this->subject($subject)
                    ->markdown('emails.interview-invitation');
    }
}
