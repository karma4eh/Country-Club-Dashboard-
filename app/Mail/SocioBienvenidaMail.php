<?php

namespace App\Mail;

use App\Models\Socio;
use App\Services\QrCodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SocioBienvenidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Socio $socio;
    public string $qrPath;

    public function __construct(Socio $socio)
    {
        $this->socio = $socio;
        $this->qrPath = QrCodeService::generarQrSocio($socio);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "¡Bienvenido a Country Club! - Membresía Oficial y Carnet Digital",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.socio_bienvenida',
            with: [
                'socio' => $this->socio,
                'qrPath' => $this->qrPath,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
