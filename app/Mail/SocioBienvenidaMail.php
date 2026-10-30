<?php

namespace App\Mail;

use App\Models\Socio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SocioBienvenidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Socio $socio;

    public function __construct(Socio $socio)
    {
        $this->socio = $socio;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "¡Bienvenido a Country Club! - Asignación de Acción #{$this->socio->accion}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.socio_bienvenida',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
