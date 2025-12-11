<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('solicitudes_reposicion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('almacen_id')->constrained('almacenes')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad_solicitada');
            $table->integer('cantidad_recibida')->default(0);
            $table->enum('estado', [
                'pendiente',
                'aceptada',
                'rechazada',
                'en_transito',
                'recibida'
            ])->default('pendiente');
            $table->string('almacen_externo_id')->nullable();
            $table->text('notas')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitudes_reposicion');
    }
};
