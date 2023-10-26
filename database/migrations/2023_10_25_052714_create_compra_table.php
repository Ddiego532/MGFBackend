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
        Schema::create('compra', function (Blueprint $table) {
            $table->id('id_compra');
            $table->string('email_titular', 50);
            $table->integer('total');
            $table->timestamp('fecha_inicio');
            $table->integer('status')->default(0);
            $table->timestamp('fecha_confirmacion')->nullable();
            $table->string('id_sesion', 300)->default('0');
            $table->unsignedBigInteger('id_carrito')->nullable();
            $table->timestamps();
            
            $table->foreign('email_titular')->references('email')->on('usuario')->onDelete('cascade');
            $table->foreign('id_carrito')->references('id_carrito')->on('carrito')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compra');
    }
};
