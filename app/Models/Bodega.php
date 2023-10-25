<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model{

    use HasFactory;

    protected $table = 'bodega'; 
    protected $primaryKey = 'id_bodega';

    public $timestamps = false;
    
    protected $fillable = [
        'id_bodega',
        'region',
        'ciudad',
        'direccion',
    ];
}
