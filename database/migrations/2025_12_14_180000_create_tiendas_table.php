<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('telefono');
            $table->string('direccion');
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->enum('estado', ['pendiente', 'activa', 'suspendida'])->default('pendiente');
            $table->integer('limite_stock_bajo')->default(20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiendas');
    }
};
