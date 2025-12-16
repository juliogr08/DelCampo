<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_reposicion', function (Blueprint $table) {
            $table->foreignId('tienda_solicitante_id')->nullable()->after('almacen_id')->constrained('tiendas')->onDelete('cascade');
            $table->enum('tipo', ['tienda_a_admin', 'admin_a_productor'])->default('admin_a_productor')->after('estado');
            $table->decimal('monto_total', 10, 2)->nullable()->after('cantidad_recibida');
            $table->boolean('pagado')->default(false)->after('monto_total');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes_reposicion', function (Blueprint $table) {
            $table->dropForeign(['tienda_solicitante_id']);
            $table->dropColumn(['tienda_solicitante_id', 'tipo', 'monto_total', 'pagado']);
        });
    }
};
