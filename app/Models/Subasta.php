<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subasta extends Model{

    use HasFactory;

    protected $table = 'subasta'; 
    protected $primaryKey = 'id_subasta';

    public $timestamps = false;
    
    protected $fillable = [
        'id_subasta',
        'id_articulo',
        'precio_venta',
        'precio_inicial',
        'fecha_apertura',
        'fecha_cierre',
        'abierto'
    ];

    public function mostrarSubasta($id_subasta){
        $subasta = $this->where('id_subasta', '=', $id_subasta)->first();
        if($subasta){
            $articulo = $this->hasMany(Articulo::class, 'id_subasta', 'id_subasta')->first();
            $subastadores = $this->getSubastadores($id_subasta);
            $datosSubasta = [
                'id_subasta' => $subasta->id_subasta,
                'id_articulo' => $subasta->id_articulo,
                'precio_venta' => $subasta->precio_venta,
                'precio_inicial' => $subasta->precio_inicial,
                'fecha_apertura' => $subasta->fecha_apertura,
                'fecha_cierre' => $subasta->fecha_cierre,
                'abierto' => $subasta->abierto,
                'articulo' => $articulo,
                'subastadores' => $subastadores
            ];
            return $datosSubasta;
        }else{
            
            return [];
        }
    }

    public function getSubastadores($id_subasta){
        $s=DB::table('subastador as s')
        ->join('usuario as u', 's.email', '=', 'u.email')
        ->select('s.*','u.nombre','u.apellido')
        ->where('s.id_subasta', $id_subasta)
        ->get();
        return $s;
    }
}
