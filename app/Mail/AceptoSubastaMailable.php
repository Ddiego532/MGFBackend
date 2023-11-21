<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AceptoSubastaMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $subasta;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usuario,$subasta)
    {
        $this->usuario = $usuario;
        $this->subasta = $subasta;

    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $nombre=$this->usuario->nombre." ".$this->usuario->apellido;
        return $this->view('correo.aceptosubasta')->with(['email' => $this->usuario->email, 'nombre' => $nombre, 'nro' => $this->subasta->id_subasta, 'total' => $this->subasta->puja])->subject('Confirmación de Subasta');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
