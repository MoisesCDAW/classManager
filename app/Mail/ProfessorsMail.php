<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProfessorsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $professorName = null;
    public $professorSurnames = null;
    public $absence = null;
    public $week = null;
    public $day = null;

    /**
     * Create a new message instance.
     */
    public function __construct($professor, $absence, $week, $day)
    {
        $this->professorName = $professor->name;
        $this->professorSurnames = $professor->surnames;
        $this->absence = $absence;
        $this->week = $week;
        $this->day = $day;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Nueva ausencia registrada!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.professors-mail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
