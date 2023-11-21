<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Usuario extends Authenticatable{

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuario'; 
    protected $primaryKey = 'id_usuario';

    public $timestamps = false;
    
    protected $fillable = [
        'email',
        'nombre',
        'apellido',
        'password',
        'fecha',
        'sexo',
        'telefono',
        'ciudad',
        'direccion',
        'tipo_usuario',
        'cartera',
    ];

    protected $hidden = [
        'password',
    ];

    public function mostrarUsuario($email){
        $usuario = $this->where('email', '=', $email)->first();
        if($usuario){
            $articulos = $this->hasMany(Articulo::class, 'email', 'email')->get();
            $datosUsuario = [
                'email' => $usuario->email,
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido,
                'fecha' => $usuario->fecha,
                'sexo' => $usuario->sexo,
                'telefono' => $usuario->telefono,
                'ciudad' => $usuario->ciudad,
                'direccion' => $usuario->direccion,
                'tipo_usuario' => $usuario->tipo_usuario,
                'cartera' => $usuario->cartera,
                'articulos' => $articulos
            ];
            return $datosUsuario;
        }else{
            
            return [];
        }
    }
}
