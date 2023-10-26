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
        Schema::create('comentario', function (Blueprint $table) {
            $table->id('id_comentario');
            $table->string('email', 50);
            $table->unsignedBigInteger('id_articulo');
            $table->text('contenido');
            $table->timestamps();
            
            $table->foreign('id_articulo')->references('id_articulo')->on('articulo')->onDelete('cascade');
            $table->foreign('email')->references('email')->on('usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comentario');
    }
};
