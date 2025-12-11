<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporte_id')->constrained()->onDelete('cascade');
            $table->string('origen');
            $table->string('destino');
            $table->dateTime('fecha_salida');
            $table->dateTime('fecha_estimada_llegada');
            $table->enum('estado_envio', ['pendiente', 'en_camino', 'entregado', 'cancelado'])->default('pendiente');
            $table->decimal('temperatura_registrada', 5, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutas');
    }
};