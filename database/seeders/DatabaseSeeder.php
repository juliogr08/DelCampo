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

        // Usar seeders específicos para poblar las tablas con todos los campos requeridos
        $this->call([
            ProductosSeeder::class,
            AlmacenSeeder::class,
            AdminSeeder::class,
            RolesPermisosSeeder::class,
        ]);

        // Insertar transportes
        DB::table('transportes')->insert([
            [
                'placa_vehiculo'   => 'ABC-123',
                'conductor'        => 'Conductor 1',
                'capacidad_carga'  => 3000,
            ],
            [
                'placa_vehiculo'   => 'DEF-456',
                'conductor'        => 'Conductor 2',
                'capacidad_carga'  => 5000,
            ],
            [
                'placa_vehiculo'   => 'GHI-789',
                'conductor'        => 'Conductor 3',
                'capacidad_carga'  => 1000,
            ],
        ]);

        // (Opcional) Podrías agregar aquí seeders específicos para rutas si los creas más adelante.

        echo "✅ Datos de prueba insertados correctamente!\n";
    }
}
