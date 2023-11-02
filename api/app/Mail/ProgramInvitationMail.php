<?php

namespace App\Mail;

use App\Models\Program;
use App\Models\ProgramInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProgramInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ProgramInvitation $invitation, public string $url)
    {
        $this->invitation->loadMissing('program.creator');
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Quizland Invitation - ' . $this->invitation->program->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.program-invitation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
