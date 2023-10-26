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
        Schema::create('pedido', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->unsignedBigInteger('id_despachador');
            $table->unsignedBigInteger('id_compra');
            $table->string('email', 50);
            $table->integer('precio_pedido');
            $table->string('estado', 15);
            $table->text('contenido_pedido');
            $table->timestamps();

            $table->foreign('email')->references('email')->on('usuario')->onDelete('cascade');
            $table->foreign('id_compra')->references('id_compra')->on('compra')->onDelete('cascade');
            $table->foreign('id_despachador')->references('id_despachador')->on('despachador')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido');
    }
};
