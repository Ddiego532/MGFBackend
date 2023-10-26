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
        Schema::create('subasta', function (Blueprint $table) {
            $table->id('id_subasta');
            $table->unsignedBigInteger('id_articulo');
            $table->integer('precio_venta');
            $table->integer('precio_inicial');
            $table->timestamp('fecha_apertura');
            $table->timestamp('fecha_cierre');
            $table->boolean('abierto')->default(true);
            $table->timestamps();

            $table->foreign('id_articulo')->references('id_articulo')->on('articulo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subasta');
    }
};
