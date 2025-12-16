<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tienda;
use App\Models\Producto;
use App\Models\SolicitudReposicion;
use Illuminate\Support\Facades\Hash;

class TiendasDemoSeederV2 extends Seeder
{
    public function run()
    {
        // Obtener tiendas existentes o crearlas
        $tienda1 = Tienda::where('slug', 'mercado-campesino')->first();
        $tienda2 = Tienda::where('slug', 'productos-del-valle')->first();
        $tienda3 = Tienda::where('slug', 'organicos-bolivia')->first();

        if (!$tienda1 || !$tienda2 || !$tienda3) {
            $this->command->error('Primero ejecuta TiendasDemoSeeder para crear las tiendas');
            return;
        }

        // Eliminar productos existentes de las tiendas demo (limpiar datos incorrectos)
        Producto::where('tienda_id', $tienda1->id)->delete();
        Producto::where('tienda_id', $tienda2->id)->delete();
        Producto::where('tienda_id', $tienda3->id)->delete();
        $this->command->info('✓ Productos demo anteriores eliminados');

        // 1. CREAR PRODUCTOS MAESTROS EN ADMIN (tienda_id = NULL)
        $productosMaestros = [
            // Tubérculos y verduras
            ['nombre' => 'Papa Holandesa', 'categoria' => 'tuberculos', 'precio_mayorista' => 8.00, 'precio_sugerido' => 10.00],
            ['nombre' => 'Zanahoria Fresca', 'categoria' => 'verduras', 'precio_mayorista' => 6.00, 'precio_sugerido' => 8.00],
            ['nombre' => 'Cebolla Colorada', 'categoria' => 'verduras', 'precio_mayorista' => 5.50, 'precio_sugerido' => 7.50],
            ['nombre' => 'Tomate Perita', 'categoria' => 'verduras', 'precio_mayorista' => 12.00, 'precio_sugerido' => 15.00],
            ['nombre' => 'Locoto Fresco', 'categoria' => 'verduras', 'precio_mayorista' => 14.00, 'precio_sugerido' => 18.00],
            // Frutas
            ['nombre' => 'Naranja de Bermejo', 'categoria' => 'frutas', 'precio_mayorista' => 9.00, 'precio_sugerido' => 12.00],
            ['nombre' => 'Mandarina Dulce', 'categoria' => 'frutas', 'precio_mayorista' => 10.00, 'precio_sugerido' => 14.00],
            ['nombre' => 'Plátano de Yungas', 'categoria' => 'frutas', 'precio_mayorista' => 7.00, 'precio_sugerido' => 10.00],
            ['nombre' => 'Mango Criollo', 'categoria' => 'frutas', 'precio_mayorista' => 15.00, 'precio_sugerido' => 20.00],
            ['nombre' => 'Lima Ácida', 'categoria' => 'frutas', 'precio_mayorista' => 6.00, 'precio_sugerido' => 8.00],
            // Orgánicos
            ['nombre' => 'Quinua Real', 'categoria' => 'granos', 'precio_mayorista' => 22.00, 'precio_sugerido' => 30.00],
            ['nombre' => 'Miel de Abeja Pura', 'categoria' => 'miel', 'precio_mayorista' => 35.00, 'precio_sugerido' => 45.00],
            ['nombre' => 'Huevos de Campo (docena)', 'categoria' => 'huevos', 'precio_mayorista' => 18.00, 'precio_sugerido' => 25.00],
            ['nombre' => 'Amaranto Premium', 'categoria' => 'granos', 'precio_mayorista' => 20.00, 'precio_sugerido' => 28.00],
            ['nombre' => 'Cañahua Molida', 'categoria' => 'granos', 'precio_mayorista' => 24.00, 'precio_sugerido' => 32.00],
        ];

        $maestrosCreados = [];
        foreach ($productosMaestros as $prod) {
            $maestro = Producto::create([
                'tienda_id' => null, // PRODUCTO MAESTRO DEL ADMIN
                'nombre' => $prod['nombre'],
                'descripcion' => 'Producto de calidad del campo boliviano',
                'categoria' => $prod['categoria'],
                'precio' => $prod['precio_sugerido'],
                'precio_mayorista' => $prod['precio_mayorista'],
                'precio_sugerido' => $prod['precio_sugerido'],
                'stock' => 500, // Stock en almacén del admin
                'unidad_medida' => $prod['categoria'] === 'miel' ? 'l' : ($prod['categoria'] === 'huevos' ? 'unidad' : 'kg'),
                'activo' => true,
                'destacado' => false,
                'estado_aprobacion' => 'aprobado',
            ]);
            $maestrosCreados[$prod['nombre']] = $maestro;
        }
        $this->command->info('✓ ' . count($maestrosCreados) . ' productos maestros creados en admin');

        // 2. TIENDAS ADOPTAN PRODUCTOS Y SOLICITAN STOCK
        $asignaciones = [
            $tienda1->id => [ // Mercado Campesino - tubérculos y verduras
                ['maestro' => 'Papa Holandesa', 'precio' => 10.00, 'stock' => 100, 'destacado' => true],
                ['maestro' => 'Zanahoria Fresca', 'precio' => 8.00, 'stock' => 80, 'destacado' => true],
                ['maestro' => 'Cebolla Colorada', 'precio' => 7.50, 'stock' => 120],
                ['maestro' => 'Tomate Perita', 'precio' => 15.00, 'stock' => 60, 'destacado' => true],
                ['maestro' => 'Locoto Fresco', 'precio' => 18.00, 'stock' => 40],
            ],
            $tienda2->id => [ // Productos del Valle - frutas
                ['maestro' => 'Naranja de Bermejo', 'precio' => 12.00, 'stock' => 150, 'destacado' => true],
                ['maestro' => 'Mandarina Dulce', 'precio' => 14.00, 'stock' => 100, 'destacado' => true],
                ['maestro' => 'Plátano de Yungas', 'precio' => 10.00, 'stock' => 80],
                ['maestro' => 'Mango Criollo', 'precio' => 20.00, 'stock' => 50, 'destacado' => true],
                ['maestro' => 'Lima Ácida', 'precio' => 8.00, 'stock' => 70],
            ],
            $tienda3->id => [ // Orgánicos Bolivia - granos y miel
                ['maestro' => 'Quinua Real', 'precio' => 30.00, 'stock' => 60, 'destacado' => true],
                ['maestro' => 'Miel de Abeja Pura', 'precio' => 45.00, 'stock' => 40, 'destacado' => true],
                ['maestro' => 'Huevos de Campo (docena)', 'precio' => 25.00, 'stock' => 50],
                ['maestro' => 'Amaranto Premium', 'precio' => 28.00, 'stock' => 35, 'destacado' => true],
                ['maestro' => 'Cañahua Molida', 'precio' => 32.00, 'stock' => 25],
            ],
        ];

        $productosCreados = 0;
        $solicitudesCreadas = 0;

        foreach ($asignaciones as $tiendaId => $productos) {
            $tienda = Tienda::find($tiendaId);
            
            foreach ($productos as $prod) {
                $maestro = $maestrosCreados[$prod['maestro']];
                
                // Crear producto adoptado por la tienda (referencia al maestro)
                $productoTienda = Producto::create([
                    'tienda_id' => $tiendaId,
                    'producto_maestro_id' => $maestro->id, // Referencia al producto del admin
                    'nombre' => $maestro->nombre,
                    'descripcion' => 'Disponible en ' . $tienda->nombre,
                    'categoria' => $maestro->categoria,
                    'precio' => $prod['precio'], // Precio que pone la tienda
                    'precio_mayorista' => $maestro->precio_mayorista,
                    'stock' => $prod['stock'],
                    'unidad_medida' => $maestro->unidad_medida,
                    'activo' => true, // Activo porque ya recibió stock
                    'destacado' => $prod['destacado'] ?? false,
                    'estado_aprobacion' => 'aprobado',
                ]);
                $productosCreados++;

                // Crear solicitud de reposición RECIBIDA (historial)
                SolicitudReposicion::create([
                    'tienda_solicitante_id' => $tiendaId,
                    'producto_id' => $productoTienda->id,
                    'cantidad_solicitada' => $prod['stock'],
                    'cantidad_recibida' => $prod['stock'],
                    'monto_total' => $maestro->precio_mayorista * $prod['stock'],
                    'pagado' => true,
                    'estado' => 'recibida',
                    'tipo' => 'tienda_a_admin',
                    'notas' => 'Solicitud inicial de stock - aprobada y entregada',
                    'fecha_respuesta' => now()->subDays(rand(1, 2)),
                    'created_at' => now()->subDays(rand(3, 10)),
                    'updated_at' => now()->subDays(rand(1, 2)),
                ]);
                $solicitudesCreadas++;

                // Descontar del stock maestro (admin envió a la tienda)
                $maestro->decrement('stock', $prod['stock']);
            }
        }

        $this->command->info("✓ $productosCreados productos adoptados por tiendas");
        $this->command->info("✓ $solicitudesCreadas solicitudes de stock registradas (aprobadas)");
        $this->command->info('');
        $this->command->info('=== FLUJO COMPLETO REGISTRADO ===');
        $this->command->info('Admin → Productos Maestros (stock 500)');
        $this->command->info('Tiendas → Adoptaron productos y solicitaron stock');
        $this->command->info('Admin → Aprobó solicitudes y envió stock');
        $this->command->info('Todo queda registrado en solicitudes_reposicion');
    }
}
