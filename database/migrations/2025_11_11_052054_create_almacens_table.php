<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('almacenes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_almacen');
            $table->string('ubicacion');
            $table->decimal('capacidad', 10, 2); // en metros cúbicos o peso
            $table->decimal('temperatura_actual', 5, 2)->nullable();
            $table->string('responsable');
            $table->string('telefono_contacto')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('almacenes');
    }
};