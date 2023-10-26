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
        Schema::create('articulo', function (Blueprint $table) {
            $table->id('id_articulo');
            $table->string('email', 50);
            $table->string('marca', 50);
            $table->integer('precio');
            $table->string('nombre_articulo', 50);
            $table->integer('cantidad');
            $table->string('tipo_articulo', 50);
            $table->text('descripcion')->nullable();
            $table->string('imagen_path', 100)->default('none');
            $table->integer('en_subasta')->default(0);
            $table->unsignedBigInteger('id_subasta')->nullable();
            $table->timestamps();

            $table->foreign('email')->references('email')->on('usuario')->onDelete('cascade');
            $table->foreign('id_subasta')->references('id_subasta')->on('subasta')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('articulo');
    }
};
