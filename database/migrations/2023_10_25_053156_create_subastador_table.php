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
        Schema::create('subastador', function (Blueprint $table) {
            $table->id('id_puja');
            $table->unsignedBigInteger('id_subasta');
            $table->string('email', 50);
            $table->integer('puja')->default(0);
            $table->timestamp('fecha_puja');
            $table->timestamps();

            $table->foreign('id_subasta')->references('id_subasta')->on('subasta')->onDelete('cascade');
            $table->foreign('email')->references('email')->on('usuario')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subastador');
    }
};
