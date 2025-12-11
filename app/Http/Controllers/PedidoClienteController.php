<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoClienteController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('tienda.mis-pedidos', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        $pedido->load(['almacen', 'detalles.producto']);

        return view('tienda.pedido-detalle', compact('pedido'));
    }
}
