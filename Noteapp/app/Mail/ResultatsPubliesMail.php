<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResultatsPubliesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resultat;
    public $pvData;

    /**
     * Create a new message instance.
     */
    public function __construct($resultat, $pvData)
    {
        $this->resultat = $resultat;
        $this->pvData = $pvData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Résultats Semestriels - ' . ($this->pvData['semestre']->anneeAcademique->libelle_Annee ?? ''),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.resultats',
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
