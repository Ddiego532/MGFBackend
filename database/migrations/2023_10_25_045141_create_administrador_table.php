<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrador', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('cargo', 15);
            $table->date('fecha_de_inicio');
            $table->date('fecha_de_termino');
            $table->integer('salario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('administrador');
    }
};
