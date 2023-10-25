<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subastador extends Model{

    use HasFactory;

    protected $table = 'subastador'; 
    protected $primaryKey = 'id_puja';

    public $timestamps = false;
    
    protected $fillable = [
        'id_subasta',
        'email',
        'puja',
        'fecha_puja',
    ];
}
