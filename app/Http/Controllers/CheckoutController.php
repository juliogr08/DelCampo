<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;

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

    /**
     * Genera un código QR para el pago usando Mercado Pago
     * Crea una preferencia de pago real que permite pagos con QR
     */
    public function generarQR(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:0',
            'codigo_pedido' => 'nullable|string',
        ]);

        $total = $request->total;
        $codigoPedido = $request->codigo_pedido ?? 'PED-' . time();
        
        // Configurar SDK de Mercado Pago
        SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
        
        // Crear preferencia de pago
        $preference = new Preference();
        
        // Crear item del pedido
        $item = new Item();
        $item->title = 'Pedido ' . $codigoPedido;
        $item->quantity = 1;
        $item->unit_price = (float) $total;
        $item->currency_id = 'BOB'; // Bolivianos
        
        $preference->items = array($item);
        
        // Configuraciones adicionales
        $preference->external_reference = $codigoPedido;
        $preference->notification_url = route('tienda.checkout.webhook');
        $preference->back_urls = [
            'success' => route('tienda.checkout.success'),
            'failure' => route('tienda.checkout.failure'),
            'pending' => route('tienda.checkout.pending'),
        ];
        
        // Configurar para pagos con QR
        $preference->payment_methods = [
            'excluded_payment_types' => [],
            'excluded_payment_methods' => [],
        ];
        
        // Guardar preferencia
        $preference->save();
        
        // Obtener QR code de la preferencia
        // Mercado Pago genera automáticamente un QR para pagos en efectivo
        $qrCode = $preference->qr_code ?? null;
        
        // Si no hay QR directo, generar uno con la URL de pago
        if (!$qrCode) {
            $initPoint = $preference->init_point;
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($initPoint);
        } else {
            $qrUrl = $qrCode;
        }

        return response()->json([
            'success' => true,
            'qr_url' => $qrUrl,
            'preference_id' => $preference->id,
            'init_point' => $preference->init_point ?? null,
            'codigo_pedido' => $codigoPedido,
            'total' => $total,
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

                $item['producto']->decrement('stock', $item['cantidad']);
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

    /**
     * Callback de éxito de Mercado Pago
     */
    public function success(Request $request)
    {
        $preferenceId = $request->get('preference_id');
        $paymentId = $request->get('payment_id');
        
        return redirect()->route('tienda.mis-pedidos')
            ->with('success', '¡Pago realizado exitosamente! Tu pedido está siendo procesado.');
    }

    /**
     * Callback de fallo de Mercado Pago
     */
    public function failure(Request $request)
    {
        return redirect()->route('tienda.checkout')
            ->with('error', 'El pago no pudo ser procesado. Por favor, intenta nuevamente.');
    }

    /**
     * Callback de pago pendiente de Mercado Pago
     */
    public function pending(Request $request)
    {
        return redirect()->route('tienda.mis-pedidos')
            ->with('info', 'Tu pago está pendiente de confirmación. Te notificaremos cuando sea aprobado.');
    }

    /**
     * Webhook de Mercado Pago para notificaciones de pago
     */
    public function webhook(Request $request)
    {
        // Verificar que la petición viene de Mercado Pago
        // En producción, deberías verificar la IP y la firma
        
        $data = $request->all();
        
        // Procesar notificación de pago
        if (isset($data['type']) && $data['type'] === 'payment') {
            $paymentId = $data['data']['id'] ?? null;
            
            if ($paymentId) {
                // Aquí puedes actualizar el estado del pedido según el pago
                // Por ejemplo, buscar el pedido por external_reference y actualizar su estado
                \Log::info('Webhook de Mercado Pago recibido', ['payment_id' => $paymentId, 'data' => $data]);
            }
        }
        
        return response()->json(['status' => 'ok'], 200);
    }
}
