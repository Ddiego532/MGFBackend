<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despachador extends Model{

    use HasFactory;

    protected $table = 'despachador'; 
    protected $primaryKey = 'id_despachador';

    public $timestamps = false;
    
    protected $fillable = [
        'id_despachador',
        'empresa',
        'url'
    ];
}
