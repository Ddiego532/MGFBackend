<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CambioPassMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;

    /**
     * Create a new message instance.
     *
     * @param $usuario // Asegúrate de pasar el usuario o la variable necesaria en el constructor
     */
    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $nombre=$this->usuario->nombre." ".$this->usuario->apellido;
        return $this->view('correo.cambiopass')->with(['email' => $this->usuario->email, 'nombre' => $nombre ])->subject('Cambio de Contraseña');
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
