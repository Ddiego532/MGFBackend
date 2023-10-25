<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model{

    use HasFactory;

    protected $table = 'administrador'; 
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
        'cargo',
        'fecha_de_inicio',
        'fecha_de_termino',
        'salario',
    ];

    protected $hidden = [
        'password',
    ];

    public function mostrarAdministrador($email){
        $administrador = $this->where('email', '=', $email)->first();
        if($administrador){
            $articulos = $this->hasMany(Articulo::class, 'email', 'email')->get();
            $compras = $this->hasMany(Compra::class, 'email_comprador', 'email')->get();
            $ventas = $this->hasMany(Compra::class, 'email_vendedor', 'email')->get();
            $datosAdministrador = [
                'email' => $administrador->email,
                'nombre' => $administrador->nombre,
                'apellido' => $administrador->apellido,
                'fecha' => $administrador->fecha,
                'sexo' => $administrador->sexo,
                'telefono' => $administrador->telefono,
                'direccion' => $administrador->direccion,
                'tipo_administrador' => $administrador->tipo_administrador,
                'cargo' => $administrador->cargo,
                'fecha_de_inicio' => $administrador->fecha_de_inicio,
                'fecha_de_termino' => $administrador->fecha_de_termino,
                'salario' => $administrador->salario,
                'articulos' => $articulos,
                'compras' => $compras,
                'ventas' => $ventas
            ];
            return $datosAdministrador;
        }else{
            
            return [];
        }
    }
}
