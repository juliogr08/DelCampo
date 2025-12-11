<?php

namespace Database\Seeders;

use App\Models\Almacen;
use Illuminate\Database\Seeder;

class AlmacenSeeder extends Seeder
{
    public function run(): void
    {
        Almacen::create([
            'nombre_almacen' => 'Almacén Central Santa Cruz',
            'ubicacion' => 'Av. Cristo Redentor, Santa Cruz de la Sierra',
            'latitud' => -17.7833,
            'longitud' => -63.1821,
            'capacidad' => 500,
            'unidad_capacidad' => 'm2',
            'tipo_almacenamiento' => 'ambiente',
            'temperatura_actual' => 22.0,
            'responsable' => 'Juan Pérez',
            'telefono_contacto' => '+591 70000001',
            'activo' => true,
            'es_principal' => true,
        ]);

        Almacen::create([
            'nombre_almacen' => 'Almacén Refrigerado Norte',
            'ubicacion' => 'Zona Norte, 4to Anillo',
            'latitud' => -17.7500,
            'longitud' => -63.1700,
            'capacidad' => 200,
            'unidad_capacidad' => 'm2',
            'tipo_almacenamiento' => 'refrigerado',
            'temperatura_actual' => 5.0,
            'responsable' => 'María García',
            'telefono_contacto' => '+591 70000002',
            'activo' => true,
            'es_principal' => false,
        ]);
    }
}
