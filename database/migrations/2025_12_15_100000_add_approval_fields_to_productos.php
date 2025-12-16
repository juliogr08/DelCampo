<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            // Estado de aprobación para productos propuestos por tiendas
            $table->enum('estado_aprobacion', ['pendiente', 'aprobado', 'rechazado'])
                  ->default('aprobado')
                  ->after('activo');
            
            // Quién propuso el producto (si fue propuesto por una tienda)
            $table->foreignId('propuesto_por_tienda_id')
                  ->nullable()
                  ->after('estado_aprobacion')
                  ->constrained('tiendas')
                  ->nullOnDelete();
            
            // Relación con producto maestro (para productos de tienda que adoptan uno del admin)
            $table->foreignId('producto_maestro_id')
                  ->nullable()
                  ->after('propuesto_por_tienda_id')
                  ->constrained('productos')
                  ->nullOnDelete();
            
            // Precio mayorista (precio base del admin, las tiendas ponen su precio de venta)
            $table->decimal('precio_mayorista', 10, 2)
                  ->nullable()
                  ->after('precio');
            
            // Motivo de rechazo cuando admin rechaza una propuesta
            $table->text('motivo_rechazo')
                  ->nullable()
                  ->after('estado_aprobacion');
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['propuesto_por_tienda_id']);
            $table->dropForeign(['producto_maestro_id']);
            $table->dropColumn([
                'estado_aprobacion',
                'propuesto_por_tienda_id',
                'producto_maestro_id',
                'precio_mayorista',
                'motivo_rechazo'
            ]);
        });
    }
};
