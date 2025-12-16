<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\SolicitudReposicion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tienda = auth()->user()->tienda;
        
        if (!$tienda) {
            return redirect()->route('tienda.home')
                ->with('error', 'No tienes una tienda registrada.');
        }

        $totalProductos = Producto::where('tienda_id', $tienda->id)->count();
        $productosActivos = Producto::where('tienda_id', $tienda->id)->where('activo', true)->count();
        
        // Productos con stock bajo (incluye inactivos con stock 0 que necesitan reposición)
        $limiteStockBajo = $tienda->limite_stock_bajo ?? 5;
        $productosStockBajo = Producto::where('tienda_id', $tienda->id)
            ->where('stock', '<=', $limiteStockBajo)
            ->get();

        $solicitudesPendientes = SolicitudReposicion::where('tienda_solicitante_id', $tienda->id)
            ->where('estado', 'pendiente')
            ->count();

        // Ventas del día - solo pedidos confirmados o posteriores
        $ventasHoy = Pedido::whereHas('detalles.producto', function($q) use ($tienda) {
            $q->where('tienda_id', $tienda->id);
        })
        ->whereDate('created_at', today())
        ->whereIn('estado', ['confirmado', 'preparando', 'listo', 'entregado'])
        ->count();

        // Ingresos del mes - solo pedidos confirmados (no pendientes ni cancelados)
        $ingresosMes = Pedido::whereHas('detalles.producto', function($q) use ($tienda) {
            $q->where('tienda_id', $tienda->id);
        })
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->whereIn('estado', ['confirmado', 'preparando', 'listo', 'entregado'])
        ->sum('total');

        $ultimosProductos = Producto::where('tienda_id', $tienda->id)
            ->latest()
            ->take(5)
            ->get();

        $ultimasSolicitudes = SolicitudReposicion::where('tienda_solicitante_id', $tienda->id)
            ->with('producto')
            ->latest()
            ->take(5)
            ->get();

        return view('tienda-panel.dashboard', compact(
            'tienda',
            'totalProductos',
            'productosActivos',
            'productosStockBajo',
            'solicitudesPendientes',
            'ventasHoy',
            'ingresosMes',
            'ultimosProductos',
            'ultimasSolicitudes'
        ));
    }
}
