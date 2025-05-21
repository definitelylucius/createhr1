<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\JobApplication;

class DemoInstructions extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $data;

    public function __construct(JobApplication $application, array $data)
    {
        $this->application = $application;
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Practical Demo Instructions - ' . $this->application->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.demo_instructions',
            with: [
                'application' => $this->application,
                'data' => $this->data
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}