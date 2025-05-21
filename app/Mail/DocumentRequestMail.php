<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\JobApplication;

class DocumentRequestMail extends Mailable
{
    public $application;
    public $documents;
    public $deadline;
    public $instructions;

    public function __construct($application, $documents, $deadline, $instructions)
    {
        $this->application = $application;
        $this->documents = $documents;
        $this->deadline = $deadline;
        $this->instructions = $instructions;
    }

    public function build()
    {
        return $this->view('emails.document_request')
            ->with([
                'application' => $this->application,
                'documents' => $this->documents,
                'deadline' => $this->deadline,
                'instructions' => $this->instructions,
                'uploadUrl' => route('applicant.documents.upload', $this->application->id),

            ]);
    }
}