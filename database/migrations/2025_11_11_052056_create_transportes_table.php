<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transportes', function (Blueprint $table) {
            $table->id();
            $table->string('placa_vehiculo')->unique();
            $table->string('conductor');
            $table->decimal('capacidad_carga', 10, 2);
            $table->string('unidad_carga')->default('kg'); // Nuevo campo
            $table->string('tipo_temperatura')->default('ambiente'); // Nuevo campo
            $table->decimal('temperatura_minima', 5, 2)->nullable();
            $table->decimal('temperatura_maxima', 5, 2)->nullable();
            $table->enum('estado', ['disponible', 'en_mantenimiento', 'en_ruta'])->default('disponible');
            $table->string('telefono_conductor')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transportes');
    }
};