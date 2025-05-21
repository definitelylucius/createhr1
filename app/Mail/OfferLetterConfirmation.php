<?php

namespace App\Mail;

use App\Models\OfferLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferLetterConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $offerLetter;

    public function __construct(OfferLetter $offerLetter)
    {
        $this->offerLetter = $offerLetter;
    }

    public function build()
    {
        return $this->subject('Offer Letter Accepted - ' . $this->offerLetter->candidate->full_name)
                    ->markdown('emails.offer-letter-confirmation');
    }
}