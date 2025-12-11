<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('rol', 'cliente');

        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->buscar}%")
                  ->orWhere('email', 'like', "%{$request->buscar}%")
                  ->orWhere('telefono', 'like', "%{$request->buscar}%");
            });
        }

        $clientes = $query->withCount('pedidos')->latest()->paginate(15);

        return view('admin.clientes.index', compact('clientes'));
    }

    public function show(User $cliente)
    {
        $cliente->load(['pedidos' => function($q) {
            $q->latest()->take(10);
        }]);

        $estadisticas = [
            'total_pedidos' => $cliente->pedidos()->count(),
            'total_gastado' => $cliente->pedidos()
                ->whereIn('estado', ['confirmado', 'preparando', 'listo', 'entregado'])
                ->sum('total'),
        ];

        return view('admin.clientes.show', compact('cliente', 'estadisticas'));
    }
}
