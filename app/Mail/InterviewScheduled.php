<?php

namespace App\Mail;

use App\Models\FinalInterview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public $interview;

    public function __construct(FinalInterview $interview)
    {
        $this->interview = $interview;
    }

    public function build()
    {
        return $this->subject('Final Interview Scheduled')
                    ->markdown('emails.interview-scheduled');
    }
}