<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IssueResolvedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $issue;

    public function __construct($issue)
    {
        $this->issue = $issue;
    }

    public function build()
    {
        return $this->subject('Your Issue Has Been Resolved')
            ->markdown('emails.issue_resolved');
    }
}
