<?php

namespace App\Mail;

use App\Models\Pago;
use App\Models\Socio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PagoReciboMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pago $pago;
    public Socio $socio;
    public float $tasa;
    public float $montoBs;

    public function __construct(Pago $pago, Socio $socio, float $tasa = 1.0, float $montoBs = 0.0)
    {
        $this->pago = $pago;
        $this->socio = $socio;
        $this->tasa = $tasa > 0 ? $tasa : 1.0;
        $this->montoBs = $montoBs > 0 ? $montoBs : (float) $pago->monto * $this->tasa;
    }

    public function envelope(): Envelope
    {
        $reciboNum = str_pad($this->pago->id, 6, '0', STR_PAD_LEFT);
        return new Envelope(
            subject: "Comprobante Oficial de Pago #REC-{$reciboNum} - Country Club C.A.",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pago_recibo',
            with: [
                'pago' => $this->pago,
                'socio' => $this->socio,
                'tasa' => $this->tasa,
                'montoBs' => $this->montoBs,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
