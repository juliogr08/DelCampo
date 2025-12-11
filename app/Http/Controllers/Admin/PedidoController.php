<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pedido::with(['user', 'almacen']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('codigo', 'like', "%{$request->buscar}%")
                  ->orWhereHas('user', function($q2) use ($request) {
                      $q2->where('name', 'like', "%{$request->buscar}%");
                  });
            });
        }

        $pedidos = $query->latest()->paginate(15);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        $pedido->load(['user', 'almacen', 'detalles.producto']);
        return view('admin.pedidos.show', compact('pedido'));
    }

    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,preparando,listo,entregado,cancelado'
        ]);

        $estadoAnterior = $pedido->estado;
        $pedido->update(['estado' => $request->estado]);

        return redirect()->back()
            ->with('success', "Estado cambiado de {$estadoAnterior} a {$request->estado}");
    }
}
