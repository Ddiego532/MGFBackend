<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuario')->insert([
            'email' => 'user3@gmail.com',
            'nombre' => 'user',
            'apellido' => 'lastuser',
            'fecha' => '2023-10-15',
            'sexo' => 'M',
            'telefono' => '+56 9 7376 4454',
            'ciudad' => 'Iquique',
            'direccion' => 'Vivar721',
            'tipo_usuario' => 1,
            'password' => '$2y$10$yKE28LqOGrnt2L4C89YsKuC5SOFtW7ogKe60K6q71I6pVjfwUaFpW',
        ]);

        
        DB::table('usuario')->insert([
            'email' => 'user@gmail.com',
            'nombre' => 'Name',
            'apellido' => 'LastName',
            'fecha' => '2000-09-13',
            'sexo' => 'M',
            'telefono' => '+56 9 7376 4454',
            'ciudad' => 'Iquique',
            'direccion' => 'Vivar721',
            'tipo_usuario' => 1,
            'password' => '$2y$10$Xpusj1u/Z8y76uam3qrfWO5YNSsQS6rFFPrkF77i0Unxx7vhiK0KO',
        ]);
    }
}
