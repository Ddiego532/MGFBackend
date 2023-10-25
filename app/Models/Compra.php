<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compra'; 
    protected $primaryKey = 'id_compra';

    public $timestamps = false;
    
    protected $fillable = [
        'id_compra',
        'id_carrito',
        'email_titular',
        'total',
        'fecha_inicio',
        'fecha_confirmacion',
        'status',
    ];

    public function mostrarCompra($email){
        $compras = $this->where('email_titular', '=', $email)->get();
        if($compras){
            $Compras=[];
            foreach ($compras as  $compra) {
                $articulos = $this->articulos($compra->id_carrito);
                $datosCompra = [
                    'id_compra' => $compra->id_compra,
                    'email_titular' => $compra->email_titular,
                    'total' => $compra->total,
                    'fecha_compra' => $compra->fecha_confirmacion,
                    'status' => $compra->status,
                    'articulos' => $articulos
                ]; 
                $Compras[]=$datosCompra;
            }
            return $Compras;
        }else{
            
            return [];
        }
    }

    public function articulos($id_carrito){
        $cont = DB::table('compra as c')
        ->join('contenidocarrito as o', 'c.id_carrito', '=', 'o.id_carrito')
        ->join('articulo as a', 'a.id_articulo', '=', 'o.id_articulo')
        ->select('a.*','c.*')
        ->where('c.id_carrito', $id_carrito)
        ->where('c.status', 1)
        ->get();
        return $cont;
    }
}
