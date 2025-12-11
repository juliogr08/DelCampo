<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('almacen_id')->constrained('almacenes')->onDelete('cascade');
            $table->string('codigo', 20)->unique();
            $table->enum('estado', [
                'pendiente',
                'confirmado', 
                'preparando',
                'listo',
                'entregado',
                'cancelado'
            ])->default('pendiente');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('costo_envio', 10, 2)->default(0);
            $table->decimal('distancia_km', 8, 2)->nullable();
            $table->decimal('total', 10, 2);
            $table->text('direccion_entrega');
            $table->decimal('entrega_latitud', 10, 8)->nullable();
            $table->decimal('entrega_longitud', 11, 8)->nullable();
            $table->enum('metodo_pago', ['efectivo', 'qr', 'transferencia'])->default('efectivo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};
