<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\CierreCaja;
use App\Models\Tienda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear clientes de prueba
        $clientes = [
            ['name' => 'María González', 'email' => 'maria@test.com', 'telefono' => '70001111', 'direccion' => 'Av. Banzer #500, Santa Cruz'],
            ['name' => 'Carlos Mendoza', 'email' => 'carlos@test.com', 'telefono' => '70002222', 'direccion' => 'Calle Junín #123, Centro'],
            ['name' => 'Ana Quispe', 'email' => 'ana@test.com', 'telefono' => '70003333', 'direccion' => 'Barrio Equipetrol, Calle 5'],
            ['name' => 'Roberto Vargas', 'email' => 'roberto@test.com', 'telefono' => '70004444', 'direccion' => 'Av. Cristo Redentor #800'],
            ['name' => 'Lucía Mamani', 'email' => 'lucia@test.com', 'telefono' => '70005555', 'direccion' => 'Plan 3000, UV 50'],
        ];

        foreach ($clientes as $cliente) {
            User::firstOrCreate(
                ['email' => $cliente['email']],
                [
                    'name' => $cliente['name'],
                    'password' => Hash::make('12345678'),
                    'telefono' => $cliente['telefono'],
                    'direccion' => $cliente['direccion'],
                    'rol' => 'cliente',
                ]
            );
        }
        $this->command->info('✓ 5 clientes creados');

        // 2. Obtener productos, tienda y almacen
        $productos = Producto::where('activo', true)->get();
        $tienda = Tienda::first();
        $almacen = \App\Models\Almacen::first();
        
        if ($productos->isEmpty()) {
            $this->command->error('No hay productos activos');
            return;
        }

        // 3. Crear pedidos de los últimos 7 días
        $clientesDB = User::where('rol', 'cliente')->get();
        $metodosPago = ['efectivo', 'qr', 'transferencia']; // Solo valores válidos del ENUM
        $estados = ['pendiente', 'confirmado', 'preparando', 'listo', 'entregado'];
        
        $pedidosCreados = 0;
        
        for ($dia = 6; $dia >= 0; $dia--) {
            $fecha = Carbon::now()->subDays($dia);
            $cantidadPedidos = rand(2, 5);
            
            for ($i = 0; $i < $cantidadPedidos; $i++) {
                $cliente = $clientesDB->random();
                $metodoPago = $metodosPago[array_rand($metodosPago)];
                $estado = $dia > 2 ? 'entregado' : $estados[array_rand($estados)];
                
                // Seleccionar 1-4 productos aleatorios
                $productosSeleccionados = $productos->random(rand(1, min(4, $productos->count())));
                $total = 0;
                $detalles = [];
                
                foreach ($productosSeleccionados as $producto) {
                    $cantidad = rand(1, 5);
                    $subtotal = $producto->precio * $cantidad;
                    $total += $subtotal;
                    
                    $detalles[] = [
                        'producto_id' => $producto->id,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $producto->precio,
                        'subtotal' => $subtotal,
                    ];
                }
                
                $pedido = Pedido::create([
                    'user_id' => $cliente->id,
                    'almacen_id' => $almacen ? $almacen->id : null,
                    'codigo' => 'PED-' . $fecha->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'total' => $total,
                    'subtotal' => $total,
                    'costo_envio' => 0,
                    'metodo_pago' => $metodoPago,
                    'estado' => $estado,
                    'direccion_entrega' => $cliente->direccion ?? 'Santa Cruz, Bolivia',
                    'observaciones' => null,
                    'created_at' => $fecha->copy()->setTime(rand(8, 20), rand(0, 59)),
                    'updated_at' => $fecha->copy()->setTime(rand(8, 20), rand(0, 59)),
                ]);
                
                foreach ($detalles as $detalle) {
                    DetallePedido::create(array_merge($detalle, ['pedido_id' => $pedido->id]));
                }
                
                $pedidosCreados++;
            }
        }
        $this->command->info("✓ $pedidosCreados pedidos creados (últimos 7 días)");

        // 4. Crear cierres de caja para la tienda
        if ($tienda) {
            $cierresCreados = 0;
            
            for ($dia = 6; $dia >= 1; $dia--) {
                $fecha = Carbon::now()->subDays($dia)->format('Y-m-d');
                
                // Verificar si ya existe cierre para ese día
                $existe = CierreCaja::where('tienda_id', $tienda->id)
                    ->where('fecha', $fecha)
                    ->exists();
                    
                if (!$existe) {
                    $montoApertura = rand(100, 500);
                    $efectivo = rand(200, 800);
                    $tarjeta = rand(100, 400);
                    $qr = rand(50, 200);
                    $transferencia = rand(50, 300);
                    $totalVentas = $efectivo + $tarjeta + $qr + $transferencia;
                    $esperado = $montoApertura + $efectivo;
                    $contado = $esperado + rand(-20, 20); // pequeña diferencia
                    
                    CierreCaja::create([
                        'tienda_id' => $tienda->id,
                        'fecha' => $fecha,
                        'monto_apertura' => $montoApertura,
                        'total_ventas' => $totalVentas,
                        'total_efectivo' => $efectivo,
                        'total_tarjeta' => $tarjeta,
                        'total_qr' => $qr,
                        'total_transferencia' => $transferencia,
                        'monto_contado' => $contado,
                        'diferencia' => $contado - $esperado,
                        'estado' => 'cerrada',
                        'abierta_por' => $tienda->user_id,
                        'cerrada_por' => $tienda->user_id,
                        'hora_apertura' => Carbon::parse($fecha)->setTime(8, 0),
                        'hora_cierre' => Carbon::parse($fecha)->setTime(20, 0),
                    ]);
                    $cierresCreados++;
                }
            }
            $this->command->info("✓ $cierresCreados cierres de caja creados");
        }

        $this->command->info('');
        $this->command->info('=== DATOS DE PRUEBA CREADOS ===');
        $this->command->info('Clientes: maria@test.com, carlos@test.com, ana@test.com (password: 12345678)');
    }
}
