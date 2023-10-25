<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Carrito extends Model{

    use HasFactory;

    protected $table = 'carrito'; 
    protected $primaryKey = 'id_carrito';

    public $timestamps = false;
    
    protected $fillable = [
        'id_carrito',
        'email_carro',
        'en_uso',
        'items'
    ];

    public function mostrarCarrito($id_carrito){
        $carrito = $this->where('id_carrito', '=', $id_carrito)->first();
        if($carrito){
            $articulos = $this->contenido($id_carrito);
            $datosCarrito = [
                'id_carrito' => $carrito->id_carrito,
                'email_carro' => $carrito->email_carro,
                'en_uso' => $carrito->en_uso,
                'items' => $carrito->items,
                'contenido' => $articulos
            ];
            return $datosCarrito;
        }else{
            return [];
        }
    }
    public function contenido($id_carrito){
        $cont = DB::table('carrito as c')
        ->join('contenidocarrito as o', 'c.id_carrito', '=', 'o.id_carrito')
        ->join('articulo as a', 'a.id_articulo', '=', 'o.id_articulo')
        ->select('a.*')
        ->where('c.id_carrito', $id_carrito)
        ->get();
        return $cont;
    }
}
