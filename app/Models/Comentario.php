<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model{

    use HasFactory;

    protected $table = 'comentario'; 
    protected $primaryKey = 'id_comentario';

    public $timestamps = false;
    
    protected $fillable = [
        'id_comentario',
        'email',
        'id_articulo',
        'contenido'
    ];
}
