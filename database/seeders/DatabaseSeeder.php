<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Limpiar tablas primero
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('productos')->truncate();
        DB::table('almacenes')->truncate();
        DB::table('transportes')->truncate();
        DB::table('rutas')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insertar productos
        DB::table('productos')->insert([
            ['nombre' => 'Laptop HP EliteBook', 'precio' => 1500.00, 'stock' => 15],
            ['nombre' => 'Mouse Logitech MX Master', 'precio' => 89.99, 'stock' => 50],
            ['nombre' => 'Teclado Mecánico Redragon', 'precio' => 79.99, 'stock' => 30],
            ['nombre' => 'Monitor 24" Samsung', 'precio' => 299.99, 'stock' => 20],
            ['nombre' => 'Impresora Multifuncional', 'precio' => 199.50, 'stock' => 10],
        ]);

        // Insertar almacenes
        DB::table('almacenes')->insert([
            ['nombre_almacen' => 'Almacén Central Lima', 'ubicacion' => 'Lima', 'capacidad' => 1000],
            ['nombre_almacen' => 'Almacén Norte', 'ubicacion' => 'Trujillo', 'capacidad' => 500],
            ['nombre_almacen' => 'Almacén Sur', 'ubicacion' => 'Arequipa', 'capacidad' => 750],
        ]);

        // Insertar transportes
        DB::table('transportes')->insert([
            ['tipo_transporte' => 'Camión 3T', 'placa' => 'ABC-123', 'capacidad_carga' => 3000],
            ['tipo_transporte' => 'Camión 5T', 'placa' => 'DEF-456', 'capacidad_carga' => 5000],
            ['tipo_transporte' => 'Furgoneta', 'placa' => 'GHI-789', 'capacidad_carga' => 1000],
        ]);

        // Insertar rutas
        DB::table('rutas')->insert([
            ['origen' => 'Lima', 'destino' => 'Trujillo', 'distancia_km' => 560, 'tiempo_estimado' => '8 horas'],
            ['origen' => 'Lima', 'destino' => 'Arequipa', 'distancia_km' => 1003, 'tiempo_estimado' => '14 horas'],
            ['origen' => 'Trujillo', 'destino' => 'Piura', 'distancia_km' => 320, 'tiempo_estimado' => '5 horas'],
            ['origen' => 'Arequipa', 'destino' => 'Tacna', 'distancia_km' => 350, 'tiempo_estimado' => '6 horas'],
        ]);

        echo "✅ Datos de prueba insertados correctamente!\n";
    }
}
