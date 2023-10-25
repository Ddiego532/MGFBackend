<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenido extends Model{

    use HasFactory;

    protected $table = 'contenidocarrito'; 
    protected $primaryKey = 'id_cont';

    public $timestamps = false;
    
    protected $fillable = [
        'id_carrito',
        'id_articulo'
    ];
}
