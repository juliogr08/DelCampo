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
        Schema::table('almacenes', function (Blueprint $table) {
            $table->string('unidad_capacidad')->default('m2')->after('capacidad');
            $table->string('tipo_almacenamiento')->default('ambiente')->after('temperatura_actual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('almacenes', function (Blueprint $table) {
            $table->dropColumn(['unidad_capacidad', 'tipo_almacenamiento']);
        });
    }
};
