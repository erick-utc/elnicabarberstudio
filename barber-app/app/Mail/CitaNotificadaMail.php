<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CitaNotificadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;
    public $tipo;

    /**
     * Create a new message instance.
     */
    public function __construct($cita, $tipo)
    {
        $this->cita = $cita;
        $this->tipo = $tipo;
    }

    public function build()
    {
        return $this->markdown('emails.cita_notificada')
            ->subject("Cita $this->tipo");
    }
}
