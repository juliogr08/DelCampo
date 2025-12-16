<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tienda;
use App\Models\SolicitudReposicion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TiendaController extends Controller
{
    public function index()
    {
        $tiendas = Tienda::with('user')
            ->withCount(['productos' => function($q) {
                $q->where('activo', true);
            }])
            ->orderBy('nombre')
            ->paginate(15);

        // Estadísticas generales
        $totalTiendas = Tienda::count();
        $tiendasActivas = Tienda::where('estado', 'activa')->count();
        
        // Total vendido a tiendas este mes
        $ventasMes = SolicitudReposicion::where('estado', 'recibida')
            ->where('pagado', true)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('monto_total');

        return view('admin.tiendas.index', compact('tiendas', 'totalTiendas', 'tiendasActivas', 'ventasMes'));
    }

    public function show(Tienda $tienda)
    {
        $tienda->load('user');

        // Historial de solicitudes de esta tienda
        $solicitudes = SolicitudReposicion::where('tienda_solicitante_id', $tienda->id)
            ->with('producto')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Estadísticas de esta tienda
        $stats = [
            'total_pedidos' => SolicitudReposicion::where('tienda_solicitante_id', $tienda->id)->count(),
            'total_recibidos' => SolicitudReposicion::where('tienda_solicitante_id', $tienda->id)
                ->where('estado', 'recibida')
                ->count(),
            'total_vendido' => SolicitudReposicion::where('tienda_solicitante_id', $tienda->id)
                ->where('estado', 'recibida')
                ->where('pagado', true)
                ->sum('monto_total'),
            'pendiente_pago' => SolicitudReposicion::where('tienda_solicitante_id', $tienda->id)
                ->where('estado', 'recibida')
                ->where('pagado', false)
                ->sum('monto_total'),
            'productos_activos' => $tienda->productos()->where('activo', true)->count(),
        ];

        // Ventas por mes (últimos 6 meses)
        $ventasPorMes = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $ventasPorMes[] = [
                'mes' => $fecha->format('M Y'),
                'total' => SolicitudReposicion::where('tienda_solicitante_id', $tienda->id)
                    ->where('estado', 'recibida')
                    ->whereMonth('created_at', $fecha->month)
                    ->whereYear('created_at', $fecha->year)
                    ->sum('monto_total'),
            ];
        }

        return view('admin.tiendas.show', compact('tienda', 'solicitudes', 'stats', 'ventasPorMes'));
    }
}
