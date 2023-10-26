<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarritoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('carrito')->insert([
            'id_carrito' => 4,
            'email_carro' => 'user@gmail.com',
            'en_uso' => 1,
            'items' => 1,
        ]);
    }
}
