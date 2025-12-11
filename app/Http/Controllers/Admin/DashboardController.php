<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use App\Models\SolicitudReposicion;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dias = $request->get('dias', 30);
        $fechaInicio = Carbon::now()->subDays($dias)->startOfDay();
        $fechaFin = Carbon::now()->endOfDay();

        $kpis = [
            'ventas_hoy' => Pedido::whereDate('created_at', today())
                ->whereNotIn('estado', ['cancelado'])
                ->sum('total'),
            'ventas_semana' => Pedido::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->whereNotIn('estado', ['cancelado'])
                ->sum('total'),
            'ventas_mes' => Pedido::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->whereNotIn('estado', ['cancelado'])
                ->sum('total'),
            'pedidos_hoy' => Pedido::whereDate('created_at', today())->count(),
            'pedidos_pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'promedio_venta' => Pedido::whereNotIn('estado', ['cancelado'])
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->avg('total') ?? 0,
            'clientes_nuevos' => User::where('rol', 'cliente')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->count(),
            'clientes_total' => User::where('rol', 'cliente')->count(),
            'productos_activos' => Producto::where('activo', true)->count(),
            'productos_stock_bajo' => Producto::whereRaw('stock <= stock_minimo')->count(),
        ];

        $ventasPorDia = Pedido::select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('SUM(total) as total_ventas'),
                DB::raw('COUNT(*) as cantidad_pedidos')
            )
            ->whereNotIn('estado', ['cancelado'])
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $ventasCompletas = [];
        $fechaActual = $fechaInicio->copy();
        while ($fechaActual <= $fechaFin) {
            $fechaStr = $fechaActual->format('Y-m-d');
            $venta = $ventasPorDia->firstWhere('fecha', $fechaStr);
            $ventasCompletas[] = [
                'fecha' => $fechaActual->format('d/m'),
                'total' => $venta ? (float)$venta->total_ventas : 0,
                'pedidos' => $venta ? $venta->cantidad_pedidos : 0,
            ];
            $fechaActual->addDay();
        }

        $ventasPorCategoria = DetallePedido::join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id')
            ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->select('productos.categoria', DB::raw('SUM(detalle_pedidos.subtotal) as total'))
            ->whereNotIn('pedidos.estado', ['cancelado'])
            ->whereBetween('pedidos.created_at', [$fechaInicio, $fechaFin])
            ->groupBy('productos.categoria')
            ->orderByDesc('total')
            ->get();

        $categoriasLabels = [];
        $categoriasData = [];
        $categoriasColores = [
            '#4A7C23', '#6B9B37', '#8FBC5A', '#5D4037', '#8D6E63',
            '#E67E22', '#27AE60', '#2980B9', '#8E44AD', '#E74C3C'
        ];
        
        foreach ($ventasPorCategoria as $index => $cat) {
            $nombreCategoria = Producto::CATEGORIAS[$cat->categoria] ?? ucfirst($cat->categoria);
            $categoriasLabels[] = $nombreCategoria;
            $categoriasData[] = (float)$cat->total;
        }

        $pedidosPorEstado = Pedido::select('estado', DB::raw('COUNT(*) as cantidad'))
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->groupBy('estado')
            ->get();

        $estadosLabels = [];
        $estadosData = [];
        $estadosColores = [
            'pendiente' => '#FFC107',
            'confirmado' => '#17A2B8',
            'preparando' => '#6F42C1',
            'listo' => '#20C997',
            'en_camino' => '#007BFF',
            'entregado' => '#28A745',
            'cancelado' => '#DC3545',
        ];
        $coloresEstados = [];

        foreach ($pedidosPorEstado as $estado) {
            $nombreEstado = Pedido::ESTADOS[$estado->estado] ?? ucfirst($estado->estado);
            $estadosLabels[] = $nombreEstado;
            $estadosData[] = $estado->cantidad;
            $coloresEstados[] = $estadosColores[$estado->estado] ?? '#6C757D';
        }

        $ultimosPedidos = Pedido::with('user')
            ->latest()
            ->take(8)
            ->get();

        $stockBajo = Producto::whereRaw('stock <= stock_minimo')
            ->orderByRaw('stock - stock_minimo')
            ->take(5)
            ->get();

        $chartData = [
            'ventasPorDia' => [
                'labels' => array_column($ventasCompletas, 'fecha'),
                'ventas' => array_column($ventasCompletas, 'total'),
                'pedidos' => array_column($ventasCompletas, 'pedidos'),
            ],
            'categorias' => [
                'labels' => $categoriasLabels,
                'data' => $categoriasData,
                'colores' => array_slice($categoriasColores, 0, count($categoriasLabels)),
            ],
            'estados' => [
                'labels' => $estadosLabels,
                'data' => $estadosData,
                'colores' => $coloresEstados,
            ],
        ];

        return view('admin.dashboard', compact('kpis', 'chartData', 'ultimosPedidos', 'stockBajo', 'dias'));
    }

    public function getChartData(Request $request)
    {
        $dias = $request->get('dias', 30);
        $fechaInicio = Carbon::now()->subDays($dias)->startOfDay();
        $fechaFin = Carbon::now()->endOfDay();

        $ventasPorDia = Pedido::select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('SUM(total) as total_ventas')
            )
            ->whereNotIn('estado', ['cancelado'])
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $labels = [];
        $data = [];
        $fechaActual = $fechaInicio->copy();
        
        while ($fechaActual <= $fechaFin) {
            $fechaStr = $fechaActual->format('Y-m-d');
            $labels[] = $fechaActual->format('d/m');
            $venta = $ventasPorDia->firstWhere('fecha', $fechaStr);
            $data[] = $venta ? (float)$venta->total_ventas : 0;
            $fechaActual->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
