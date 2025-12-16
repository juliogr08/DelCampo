<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cierres_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas')->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('monto_apertura', 10, 2)->default(0);
            $table->decimal('total_ventas', 10, 2)->default(0);
            $table->decimal('total_efectivo', 10, 2)->default(0);
            $table->decimal('total_tarjeta', 10, 2)->default(0);
            $table->decimal('total_qr', 10, 2)->default(0);
            $table->decimal('total_transferencia', 10, 2)->default(0);
            $table->decimal('monto_contado', 10, 2)->nullable();
            $table->decimal('diferencia', 10, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->foreignId('abierta_por')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cerrada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('hora_apertura')->nullable();
            $table->timestamp('hora_cierre')->nullable();
            $table->timestamps();
            
            // Un cierre por día por tienda
            $table->unique(['tienda_id', 'fecha']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cierres_caja');
    }
};
