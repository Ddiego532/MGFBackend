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
        Schema::create('contenidocarrito', function (Blueprint $table) {
            $table->unsignedBigInteger('id_carrito');
            $table->unsignedBigInteger('id_articulo')->nullable();
            $table->id('id_cont');
            $table->timestamps();
            
            $table->foreign('id_carrito')->references('id_carrito')->on('carrito')->onDelete('cascade');
            $table->foreign('id_articulo')->references('id_articulo')->on('articulo')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contenidocarrito');
    }
};
