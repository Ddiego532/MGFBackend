<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticuloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('articulo')->insert([
            'id_articulo' => 43,
            'email' => 'user@gmail.com',
            'marca' => 'Lee',
            'precio' => 30000,
            'nombre_articulo' => 'Pantalones Lee',
            'cantidad' => 1,
            'tipo_articulo' => 'Prenda Inferior Femenina',
            'descripcion' => 'a',
            'imagen_path' => 'images/65319dd544a75.jpg',
            'en_subasta' => 0,
            'id_subasta' => null,
        ]);

        DB::table('articulo')->insert([
            'id_articulo' => 44,
            'email' => 'user@gmail.com',
            'marca' => 'Lacoste',
            'precio' => 10000,
            'nombre_articulo' => 'Polo Lacoste',
            'cantidad' => 1,
            'tipo_articulo' => 'Prenda Superior Masculina',
            'descripcion' => 'Polo Lacoste color rojo Talla L',
            'imagen_path' => 'images/6532f209baa9b.jpg',
            'en_subasta' => 1,
            'id_subasta' => 3,
        ]);
    }
}
