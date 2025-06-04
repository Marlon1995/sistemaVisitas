<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarReporte extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $mensaje;

    public function __construct($ruta)
    {
    $this->ruta=$ruta;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Sistema control de visitas - Centro Financiero')->view('Email.reporte')
        ->attachFromStorageDisk('local', 'ReporteVisitas.xlsx');
        //->attachFromStorage($this->ruta);
    }
}
