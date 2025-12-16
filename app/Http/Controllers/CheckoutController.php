<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('tienda.carrito')
                ->with('error', 'Tu carrito está vacío');
        }

        $productos = [];
        $subtotal = 0;

        foreach ($carrito as $productoId => $cantidad) {
            $producto = Producto::find($productoId);
            if ($producto) {
                $productos[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'subtotal' => $producto->precio * $cantidad
                ];
                $subtotal += $producto->precio * $cantidad;
            }
        }

        $almacenes = Almacen::activos()->get();
        $user = auth()->user();

        return view('tienda.checkout', compact('productos', 'subtotal', 'almacenes', 'user'));
    }

    public function cotizar(Request $request)
    {
        $request->validate([
            'almacen_id' => 'required|exists:almacenes,id',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        $almacen = Almacen::find($request->almacen_id);
        $distancia = $almacen->calcularDistanciaKm($request->latitud, $request->longitud);
        
        $costoEnvio = $almacen->calcularCostoEnvio($request->latitud, $request->longitud, 6, 1);

        return response()->json([
            'distancia' => $distancia,
            'costo_envio' => $costoEnvio,
            'almacen' => $almacen->nombre_almacen
        ]);
    }

    public function confirmar(Request $request)
    {
        $request->validate([
            'almacen_id' => 'required|exists:almacenes,id',
            'direccion_entrega' => 'required|string|max:500',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'metodo_pago' => 'required|in:efectivo,qr,transferencia',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('tienda.carrito')
                ->with('error', 'Tu carrito está vacío');
        }

        $almacen = Almacen::find($request->almacen_id);

        $subtotal = 0;
        $productosData = [];

        foreach ($carrito as $productoId => $cantidad) {
            $producto = Producto::find($productoId);
            if ($producto) {
                if ($producto->stock < $cantidad) {
                    return back()->with('error', "Stock insuficiente para {$producto->nombre}");
                }
                
                $productosData[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'precio' => $producto->precio
                ];
                $subtotal += $producto->precio * $cantidad;
            }
        }

        $distancia = $almacen->calcularDistanciaKm($request->latitud, $request->longitud);
        $costoEnvio = $almacen->calcularCostoEnvio($request->latitud, $request->longitud, 6, 1);
        $total = $subtotal + $costoEnvio;

        DB::beginTransaction();
        try {
            $pedido = Pedido::create([
                'user_id' => auth()->id(),
                'almacen_id' => $almacen->id,
                'codigo' => Pedido::generarCodigo(),
                'estado' => 'pendiente',
                'subtotal' => $subtotal,
                'costo_envio' => $costoEnvio,
                'distancia_km' => $distancia,
                'total' => $total,
                'direccion_entrega' => $request->direccion_entrega,
                'entrega_latitud' => $request->latitud,
                'entrega_longitud' => $request->longitud,
                'metodo_pago' => $request->metodo_pago,
                'observaciones' => $request->observaciones,
            ]);

            foreach ($productosData as $item) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['producto']->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['precio'] * $item['cantidad'],
                ]);

                // NO descontar stock aquí - se descuenta cuando la tienda confirma el pedido
            }

            session()->forget('carrito');

            DB::commit();

            return redirect()->route('tienda.pedido.detalle', $pedido)
                ->with('success', "¡Pedido #{$pedido->codigo} creado exitosamente!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pedido. Intente nuevamente.');
        }
    }
}
