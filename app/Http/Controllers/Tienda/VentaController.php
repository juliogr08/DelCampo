<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    protected function getTienda()
    {
        return auth()->user()->tienda;
    }

    public function index()
    {
        $tienda = $this->getTienda();
        
        $pedidos = Pedido::whereHas('detalles.producto', function($q) use ($tienda) {
            $q->where('tienda_id', $tienda->id);
        })
        ->with(['user', 'detalles.producto'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('tienda-panel.ventas.index', compact('pedidos', 'tienda'));
    }

    public function show(Pedido $pedido)
    {
        $tienda = $this->getTienda();
        
        $tieneProductosTienda = $pedido->detalles()->whereHas('producto', function($q) use ($tienda) {
            $q->where('tienda_id', $tienda->id);
        })->exists();

        if (!$tieneProductosTienda) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        $detallesTienda = $pedido->detalles()->whereHas('producto', function($q) use ($tienda) {
            $q->where('tienda_id', $tienda->id);
        })->with('producto')->get();

        return view('tienda-panel.ventas.show', compact('pedido', 'detallesTienda', 'tienda'));
    }

    /**
     * Confirmar pedido - descuenta stock
     */
    public function confirmar(Pedido $pedido)
    {
        $tienda = $this->getTienda();
        
        // Verificar que el pedido tenga productos de esta tienda
        $detallesTienda = $pedido->detalles()->whereHas('producto', function($q) use ($tienda) {
            $q->where('tienda_id', $tienda->id);
        })->with('producto')->get();

        if ($detallesTienda->isEmpty()) {
            abort(403, 'No tienes permiso para confirmar este pedido.');
        }

        if ($pedido->estado !== 'pendiente') {
            return back()->with('error', 'Este pedido ya no está pendiente.');
        }

        // Verificar stock disponible antes de confirmar
        foreach ($detallesTienda as $detalle) {
            if ($detalle->producto->stock < $detalle->cantidad) {
                return back()->with('error', "Stock insuficiente para {$detalle->producto->nombre}. Disponible: {$detalle->producto->stock}");
            }
        }

        // Descontar stock de los productos de esta tienda
        foreach ($detallesTienda as $detalle) {
            $detalle->producto->decrement('stock', $detalle->cantidad);
        }

        $pedido->update(['estado' => 'confirmado']);

        return back()->with('success', "Pedido #{$pedido->codigo} confirmado. Stock descontado.");
    }

    /**
     * Cambiar estado del pedido
     */
    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $tienda = $this->getTienda();
        
        $tieneProductosTienda = $pedido->detalles()->whereHas('producto', function($q) use ($tienda) {
            $q->where('tienda_id', $tienda->id);
        })->exists();

        if (!$tieneProductosTienda) {
            abort(403, 'No tienes permiso para modificar este pedido.');
        }

        $request->validate([
            'estado' => 'required|in:confirmado,preparando,listo,entregado,cancelado'
        ]);

        $estadoAnterior = $pedido->estado;
        $nuevoEstado = $request->estado;

        // Si cancela un pedido que ya estaba confirmado, devolver stock
        if ($nuevoEstado === 'cancelado' && in_array($estadoAnterior, ['confirmado', 'preparando', 'listo'])) {
            $detallesTienda = $pedido->detalles()->whereHas('producto', function($q) use ($tienda) {
                $q->where('tienda_id', $tienda->id);
            })->with('producto')->get();

            foreach ($detallesTienda as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);
            }
        }

        $pedido->update(['estado' => $nuevoEstado]);

        return back()->with('success', "Estado del pedido actualizado a: " . Pedido::ESTADOS[$nuevoEstado]);
    }
}
