<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('codigo_barras')->unique();
            $table->decimal('precio', 10, 2);
            $table->decimal('peso', 10, 3)->nullable();
            $table->string('unidad_medida')->default('kg');
            $table->string('categoria')->default('otros');
            $table->string('temperatura_requerida')->default('ambiente');
            $table->string('lote');
            $table->date('fecha_vencimiento');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
};