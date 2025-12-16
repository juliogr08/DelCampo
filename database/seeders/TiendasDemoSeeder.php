<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tienda;
use App\Models\Producto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TiendasDemoSeeder extends Seeder
{
    public function run()
    {
        // Tienda 1: Mercado Campesino
        $user1 = User::firstOrCreate(
            ['email' => 'mercadocampesino@test.com'],
            [
                'name' => 'Juan Pérez',
                'password' => Hash::make('12345678'),
                'telefono' => '70100100',
                'rol' => 'tienda',
            ]
        );

        $tienda1 = Tienda::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'nombre' => 'Mercado Campesino',
                'slug' => 'mercado-campesino',
                'descripcion' => 'Productos frescos directos del campo boliviano. Verduras, tubérculos y frutas de la mejor calidad.',
                'direccion' => 'Av. Banzer Km 5, Santa Cruz',
                'telefono' => '70100100',
                'latitud' => -17.7833,
                'longitud' => -63.1821,
                'estado' => 'activa',
            ]
        );

        // Tienda 2: Productos del Valle
        $user2 = User::firstOrCreate(
            ['email' => 'productosdelvalle@test.com'],
            [
                'name' => 'María Condori',
                'password' => Hash::make('12345678'),
                'telefono' => '70200200',
                'rol' => 'tienda',
            ]
        );

        $tienda2 = Tienda::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'nombre' => 'Productos del Valle',
                'slug' => 'productos-del-valle',
                'descripcion' => 'Frutas y verduras frescas de los valles de Cochabamba.',
                'direccion' => 'Mercado La Ramada, Puesto 45',
                'telefono' => '70200200',
                'latitud' => -17.7900,
                'longitud' => -63.1950,
                'estado' => 'activa',
            ]
        );

        // Tienda 3: Orgánicos Bolivia
        $user3 = User::firstOrCreate(
            ['email' => 'organicosbolivia@test.com'],
            [
                'name' => 'Pedro Mamani',
                'password' => Hash::make('12345678'),
                'telefono' => '70300300',
                'rol' => 'tienda',
            ]
        );

        $tienda3 = Tienda::firstOrCreate(
            ['user_id' => $user3->id],
            [
                'nombre' => 'Orgánicos Bolivia',
                'slug' => 'organicos-bolivia',
                'descripcion' => 'Productos 100% orgánicos certificados. Miel, quinua y granos andinos.',
                'direccion' => 'Zona Equipetrol, Calle 10 #200',
                'telefono' => '70300300',
                'latitud' => -17.7750,
                'longitud' => -63.1770,
                'estado' => 'activa',
            ]
        );

        $this->command->info('✓ 3 tiendas creadas');

        // Productos para Mercado Campesino
        $productosTienda1 = [
            ['nombre' => 'Papa Holandesa Fresca', 'categoria' => 'tuberculos', 'precio' => 10.00, 'stock' => 100, 'destacado' => true],
            ['nombre' => 'Zanahoria del Campo', 'categoria' => 'verduras', 'precio' => 8.00, 'stock' => 80, 'destacado' => true],
            ['nombre' => 'Cebolla Colorada', 'categoria' => 'verduras', 'precio' => 7.50, 'stock' => 120],
            ['nombre' => 'Tomate Perita Premium', 'categoria' => 'verduras', 'precio' => 15.00, 'stock' => 60, 'destacado' => true],
            ['nombre' => 'Locoto Fresco', 'categoria' => 'verduras', 'precio' => 18.00, 'stock' => 40],
        ];

        foreach ($productosTienda1 as $prod) {
            Producto::create([
                'tienda_id' => $tienda1->id,
                'nombre' => $prod['nombre'],
                'descripcion' => 'Producto de calidad de ' . $tienda1->nombre,
                'categoria' => $prod['categoria'],
                'precio' => $prod['precio'],
                'stock' => $prod['stock'],
                'unidad_medida' => 'kg',
                'activo' => true,
                'destacado' => $prod['destacado'] ?? false,
                'estado_aprobacion' => 'aprobado',
            ]);
        }

        // Productos para Productos del Valle
        $productosTienda2 = [
            ['nombre' => 'Naranja Dulce de Bermejo', 'categoria' => 'frutas', 'precio' => 12.00, 'stock' => 150, 'destacado' => true],
            ['nombre' => 'Mandarina Jugosa', 'categoria' => 'frutas', 'precio' => 14.00, 'stock' => 100, 'destacado' => true],
            ['nombre' => 'Plátano de Yungas', 'categoria' => 'frutas', 'precio' => 10.00, 'stock' => 80],
            ['nombre' => 'Mango Criollo', 'categoria' => 'frutas', 'precio' => 20.00, 'stock' => 50, 'destacado' => true],
            ['nombre' => 'Lima Ácida', 'categoria' => 'frutas', 'precio' => 8.00, 'stock' => 70],
        ];

        foreach ($productosTienda2 as $prod) {
            Producto::create([
                'tienda_id' => $tienda2->id,
                'nombre' => $prod['nombre'],
                'descripcion' => 'Producto de calidad de ' . $tienda2->nombre,
                'categoria' => $prod['categoria'],
                'precio' => $prod['precio'],
                'stock' => $prod['stock'],
                'unidad_medida' => 'kg',
                'activo' => true,
                'destacado' => $prod['destacado'] ?? false,
                'estado_aprobacion' => 'aprobado',
            ]);
        }

        // Productos para Orgánicos Bolivia
        $productosTienda3 = [
            ['nombre' => 'Quinua Real Orgánica', 'categoria' => 'granos', 'precio' => 30.00, 'stock' => 60, 'destacado' => true],
            ['nombre' => 'Miel Pura de Abeja', 'categoria' => 'miel', 'precio' => 45.00, 'stock' => 40, 'destacado' => true],
            ['nombre' => 'Huevos de Campo (12 unid)', 'categoria' => 'huevos', 'precio' => 25.00, 'stock' => 50],
            ['nombre' => 'Amaranto Premium', 'categoria' => 'granos', 'precio' => 28.00, 'stock' => 35, 'destacado' => true],
            ['nombre' => 'Cañahua Molida', 'categoria' => 'granos', 'precio' => 32.00, 'stock' => 25],
        ];

        foreach ($productosTienda3 as $prod) {
            Producto::create([
                'tienda_id' => $tienda3->id,
                'nombre' => $prod['nombre'],
                'descripcion' => 'Producto de calidad de ' . $tienda3->nombre,
                'categoria' => $prod['categoria'],
                'precio' => $prod['precio'],
                'stock' => $prod['stock'],
                'unidad_medida' => $prod['categoria'] === 'miel' ? 'l' : ($prod['categoria'] === 'huevos' ? 'unidad' : 'kg'),
                'activo' => true,
                'destacado' => $prod['destacado'] ?? false,
                'estado_aprobacion' => 'aprobado',
            ]);
        }

        $this->command->info('✓ 15 productos creados (5 por tienda)');
        $this->command->info('');
        $this->command->info('=== CREDENCIALES DE TIENDAS ===');
        $this->command->info('mercadocampesino@test.com / 12345678');
        $this->command->info('productosdelvalle@test.com / 12345678');
        $this->command->info('organicosbolivia@test.com / 12345678');
    }
}
