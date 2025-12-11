<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        return view('admin.reportes.index');
    }

    public function ventas(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fechaHasta = $request->get('fecha_hasta', now()->format('Y-m-d'));

        $data = $this->getVentasData($fechaDesde, $fechaHasta);

        return view('admin.reportes.ventas', array_merge($data, [
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta
        ]));
    }

    public function ventasPdf(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fechaHasta = $request->get('fecha_hasta', now()->format('Y-m-d'));

        $data = $this->getVentasData($fechaDesde, $fechaHasta);
        $data['fechaDesde'] = $fechaDesde;
        $data['fechaHasta'] = $fechaHasta;

        $pdf = Pdf::loadView('admin.reportes.pdf.ventas', $data);
        return $pdf->download('reporte_ventas_' . $fechaDesde . '_' . $fechaHasta . '.pdf');
    }

    public function ventasExcel(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fechaHasta = $request->get('fecha_hasta', now()->format('Y-m-d'));

        $data = $this->getVentasData($fechaDesde, $fechaHasta);

        $filename = 'reporte_ventas_' . $fechaDesde . '_' . $fechaHasta . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['REPORTE DE VENTAS']);
            fputcsv($file, ['']);
            fputcsv($file, ['Total Pedidos', $data['resumen']['total_pedidos']]);
            fputcsv($file, ['Total Ventas (Bs)', number_format($data['resumen']['total_ventas'], 2)]);
            fputcsv($file, ['Promedio por Pedido (Bs)', number_format($data['resumen']['promedio_pedido'], 2)]);
            fputcsv($file, ['Total Envíos (Bs)', number_format($data['resumen']['total_envios'], 2)]);
            fputcsv($file, ['']);
            
            fputcsv($file, ['DETALLE POR DÍA']);
            fputcsv($file, ['Fecha', 'Cantidad Pedidos', 'Total Ventas (Bs)']);
            foreach ($data['ventasPorDia'] as $dia) {
                fputcsv($file, [$dia->fecha, $dia->cantidad, number_format($dia->total, 2)]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getVentasData($fechaDesde, $fechaHasta)
    {
        $ventas = Pedido::whereNotIn('estado', ['cancelado'])
            ->whereDate('created_at', '>=', $fechaDesde)
            ->whereDate('created_at', '<=', $fechaHasta)
            ->get();

        $resumen = [
            'total_pedidos' => $ventas->count(),
            'total_ventas' => $ventas->sum('total'),
            'promedio_pedido' => $ventas->count() > 0 ? $ventas->avg('total') : 0,
            'total_envios' => $ventas->sum('costo_envio'),
        ];

        $ventasPorDia = Pedido::whereNotIn('estado', ['cancelado'])
            ->whereDate('created_at', '>=', $fechaDesde)
            ->whereDate('created_at', '<=', $fechaHasta)
            ->selectRaw('DATE(created_at) as fecha, SUM(total) as total, COUNT(*) as cantidad')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return compact('resumen', 'ventasPorDia');
    }

    public function productos(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fechaHasta = $request->get('fecha_hasta', now()->format('Y-m-d'));

        $data = $this->getProductosData($fechaDesde, $fechaHasta);

        return view('admin.reportes.productos', array_merge($data, [
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta
        ]));
    }

    public function productosPdf(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fechaHasta = $request->get('fecha_hasta', now()->format('Y-m-d'));

        $data = $this->getProductosData($fechaDesde, $fechaHasta);
        $data['fechaDesde'] = $fechaDesde;
        $data['fechaHasta'] = $fechaHasta;

        $pdf = Pdf::loadView('admin.reportes.pdf.productos', $data);
        return $pdf->download('reporte_productos_' . $fechaDesde . '_' . $fechaHasta . '.pdf');
    }

    public function productosExcel(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fechaHasta = $request->get('fecha_hasta', now()->format('Y-m-d'));

        $data = $this->getProductosData($fechaDesde, $fechaHasta);

        $filename = 'reporte_productos_' . $fechaDesde . '_' . $fechaHasta . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['PRODUCTOS MÁS VENDIDOS']);
            fputcsv($file, ['']);
            fputcsv($file, ['Ranking', 'Producto', 'Precio Unitario (Bs)', 'Cantidad Vendida', 'Total Vendido (Bs)']);
            
            $ranking = 1;
            foreach ($data['productos'] as $producto) {
                fputcsv($file, [
                    $ranking++,
                    $producto->nombre,
                    number_format($producto->precio, 2),
                    $producto->cantidad_vendida,
                    number_format($producto->total_vendido, 2)
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getProductosData($fechaDesde, $fechaHasta)
    {
        $productos = DB::table('detalle_pedidos')
            ->join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id')
            ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->whereNotIn('pedidos.estado', ['cancelado'])
            ->whereDate('pedidos.created_at', '>=', $fechaDesde)
            ->whereDate('pedidos.created_at', '<=', $fechaHasta)
            ->select(
                'productos.id',
                'productos.nombre',
                'productos.precio',
                'productos.categoria',
                DB::raw('SUM(detalle_pedidos.cantidad) as cantidad_vendida'),
                DB::raw('SUM(detalle_pedidos.subtotal) as total_vendido')
            )
            ->groupBy('productos.id', 'productos.nombre', 'productos.precio', 'productos.categoria')
            ->orderByDesc('cantidad_vendida')
            ->limit(20)
            ->get();

        $porCategoria = DB::table('detalle_pedidos')
            ->join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id')
            ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->whereNotIn('pedidos.estado', ['cancelado'])
            ->whereDate('pedidos.created_at', '>=', $fechaDesde)
            ->whereDate('pedidos.created_at', '<=', $fechaHasta)
            ->select(
                'productos.categoria',
                DB::raw('SUM(detalle_pedidos.cantidad) as cantidad'),
                DB::raw('SUM(detalle_pedidos.subtotal) as total')
            )
            ->groupBy('productos.categoria')
            ->orderByDesc('total')
            ->get();

        return compact('productos', 'porCategoria');
    }

    public function stockBajo()
    {
        $data = $this->getStockBajoData();
        return view('admin.reportes.stock-bajo', $data);
    }

    public function stockBajoPdf()
    {
        $data = $this->getStockBajoData();
        $data['fecha'] = now()->format('d/m/Y H:i');
        
        $pdf = Pdf::loadView('admin.reportes.pdf.stock-bajo', $data);
        return $pdf->download('reporte_stock_bajo_' . now()->format('Y-m-d') . '.pdf');
    }

    public function stockBajoExcel()
    {
        $data = $this->getStockBajoData();

        $filename = 'reporte_stock_bajo_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['REPORTE DE STOCK BAJO']);
            fputcsv($file, ['Generado: ' . now()->format('d/m/Y H:i')]);
            fputcsv($file, ['']);
            fputcsv($file, ['Producto', 'Categoría', 'Stock Actual', 'Stock Mínimo', 'Déficit', 'Estado']);
            
            foreach ($data['productos'] as $producto) {
                $deficit = $producto->stock_minimo - $producto->stock;
                $estado = $producto->stock == 0 ? 'AGOTADO' : 'BAJO';
                fputcsv($file, [
                    $producto->nombre,
                    Producto::CATEGORIAS[$producto->categoria] ?? $producto->categoria,
                    $producto->stock,
                    $producto->stock_minimo,
                    $deficit,
                    $estado
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getStockBajoData()
    {
        $productos = Producto::whereRaw('stock <= stock_minimo')
            ->orderByRaw('stock - stock_minimo')
            ->get();

        $resumen = [
            'total_productos' => $productos->count(),
            'agotados' => $productos->where('stock', 0)->count(),
            'criticos' => $productos->where('stock', '>', 0)->count(),
        ];

        return compact('productos', 'resumen');
    }
}
