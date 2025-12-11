<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('almacenes', function (Blueprint $table) {
            $table->decimal('latitud', 10, 8)->nullable()->after('ubicacion');
            $table->decimal('longitud', 11, 8)->nullable()->after('latitud');
            $table->boolean('es_principal')->default(false)->after('activo');
        });
    }

    public function down()
    {
        Schema::table('almacenes', function (Blueprint $table) {
            $table->dropColumn(['latitud', 'longitud', 'es_principal']);
        });
    }
};
