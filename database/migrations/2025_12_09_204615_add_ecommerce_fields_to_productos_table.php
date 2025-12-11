<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('imagen')->nullable()->after('descripcion');
            $table->boolean('activo')->default(true)->after('stock');
            $table->boolean('destacado')->default(false)->after('activo');
            $table->integer('stock_minimo')->default(5)->after('destacado');
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['imagen', 'activo', 'destacado', 'stock_minimo']);
        });
    }
};
