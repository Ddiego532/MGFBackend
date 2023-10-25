<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model{

    use HasFactory;

    protected $table = 'articulo'; 
    protected $primaryKey = 'id_articulo';
    
    public $timestamps = false;
    
    protected $fillable = [
        'id_articulo',
        'id_subasta',
        'email',
        'marca',
        'precio',
        'tipo_articulo',
        'nombre_articulo',
        'cantidad',
        'descripcion',
        'imagen_path',
        'en_subasta'
    ];
}
