<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['admin', 'cliente'])->default('cliente')->after('email');
            $table->string('telefono', 20)->nullable()->after('rol');
            $table->text('direccion')->nullable()->after('telefono');
            $table->string('ciudad', 100)->nullable()->after('direccion');
            $table->decimal('latitud', 10, 8)->nullable()->after('ciudad');
            $table->decimal('longitud', 11, 8)->nullable()->after('latitud');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rol', 'telefono', 'direccion', 'ciudad', 'latitud', 'longitud']);
        });
    }
};
