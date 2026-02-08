<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotePublieeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $etudiant;
    public $note;
    public $evaluation;
    public $ue;

    /**
     * Create a new message instance.
     */
    public function __construct($etudiant, $note, $evaluation, $ue)
    {
        $this->etudiant = $etudiant;
        $this->note = $note;
        $this->evaluation = $evaluation;
        $this->ue = $ue;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle note disponible - ' . $this->ue->libelle . ' (' . $this->evaluation->type_Evaluation . ')',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.note_publiee',
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
