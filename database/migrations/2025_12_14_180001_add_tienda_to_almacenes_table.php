<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('almacenes', function (Blueprint $table) {
            $table->foreignId('tienda_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->boolean('es_sede_principal')->default(false)->after('es_principal');
        });
    }

    public function down(): void
    {
        Schema::table('almacenes', function (Blueprint $table) {
            $table->dropForeign(['tienda_id']);
            $table->dropColumn(['tienda_id', 'es_sede_principal']);
        });
    }
};
