<?php 

namespace App\Services;

use App\Mail\InterviewInvitation;
use App\Mail\OfferLetterEmail;
use App\Mail\OfferLetterConfirmation;
use App\Models\Candidate;
use App\Models\CalendarEvent;
use App\Models\HiringProcessStage;
use App\Models\OfferLetter;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendInterviewInvitation(Candidate $candidate, HiringProcessStage $stage, CalendarEvent $event)
    {
        $mail = new InterviewInvitation($candidate, $stage, $event);
        Mail::to($candidate->email)->send($mail);
    }

    public function sendOfferLetter(OfferLetter $offerLetter)
    {
        $mail = new OfferLetterEmail($offerLetter);
        Mail::to($offerLetter->candidate->email)->send($mail);
    }

    public function sendOfferLetterConfirmation(OfferLetter $offerLetter)
    {
        $mail = new OfferLetterConfirmation($offerLetter);
        Mail::to(config('mail.admin_address'))->send($mail);
    }
}