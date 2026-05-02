<?php

namespace App\Mail;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevaSolicitudEmpresaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Empresa $empresa,
        public User $representante,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva solicitud de registro de empresa — La Cuponera SV',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nueva-solicitud-empresa',
        );
    }
}
