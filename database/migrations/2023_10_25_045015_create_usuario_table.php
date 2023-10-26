<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('email', 50)->unique();
            $table->string('nombre', 15);
            $table->string('apellido', 15);
            $table->date('fecha');
            $table->string('sexo', 15);
            $table->string('telefono', 25);
            $table->string('ciudad', 20);
            $table->string('direccion', 50);
            $table->string('tipo_usuario', 10);
            $table->string('password', 200);
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuario');
    }
};