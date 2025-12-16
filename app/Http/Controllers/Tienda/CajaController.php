<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\CierreCaja;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CajaController extends Controller
{
    protected function getTienda()
    {
        return auth()->user()->tienda;
    }

    /**
     * Dashboard de caja - estado actual del día
     */
    public function index()
    {
        $tienda = $this->getTienda();
        $hoy = Carbon::today();
        
        // Buscar cierre de hoy o crear estado inicial
        $cajaHoy = CierreCaja::where('tienda_id', $tienda->id)
            ->where('fecha', $hoy)
            ->first();
        
        // Obtener ventas del día para esta tienda
        $ventasHoy = $this->getVentasDelDia($tienda, $hoy);
        
        return view('tienda-panel.caja.index', compact('tienda', 'cajaHoy', 'ventasHoy', 'hoy'));
    }

    /**
     * Obtener resumen de ventas del día
     */
    protected function getVentasDelDia($tienda, $fecha)
    {
        $pedidos = Pedido::whereHas('detalles.producto', function($q) use ($tienda) {
                $q->where('tienda_id', $tienda->id);
            })
            ->whereDate('created_at', $fecha)
            ->where('estado', '!=', 'cancelado')
            ->get();

        return [
            'total' => $pedidos->sum('total'),
            'efectivo' => $pedidos->where('metodo_pago', 'efectivo')->sum('total'),
            'tarjeta' => $pedidos->where('metodo_pago', 'tarjeta')->sum('total'),
            'qr' => $pedidos->where('metodo_pago', 'qr')->sum('total'),
            'transferencia' => $pedidos->where('metodo_pago', 'transferencia')->sum('total'),
            'cantidad' => $pedidos->count(),
        ];
    }

    /**
     * Abrir caja del día
     */
    public function apertura(Request $request)
    {
        $tienda = $this->getTienda();
        $hoy = Carbon::today();
        
        // Verificar que no haya caja abierta hoy
        $existe = CierreCaja::where('tienda_id', $tienda->id)
            ->where('fecha', $hoy)
            ->exists();
            
        if ($existe) {
            return back()->with('error', 'Ya existe una caja para hoy.');
        }

        $request->validate([
            'monto_apertura' => 'required|numeric|min:0',
        ]);

        CierreCaja::create([
            'tienda_id' => $tienda->id,
            'fecha' => $hoy,
            'monto_apertura' => $request->monto_apertura,
            'estado' => 'abierta',
            'abierta_por' => auth()->id(),
            'hora_apertura' => now(),
        ]);

        return back()->with('success', 'Caja abierta exitosamente. Monto inicial: ' . number_format($request->monto_apertura, 2) . ' Bs');
    }

    /**
     * Cerrar caja del día
     */
    public function cierre(Request $request)
    {
        $tienda = $this->getTienda();
        $hoy = Carbon::today();
        
        $caja = CierreCaja::where('tienda_id', $tienda->id)
            ->where('fecha', $hoy)
            ->where('estado', 'abierta')
            ->first();
            
        if (!$caja) {
            return back()->with('error', 'No hay caja abierta para cerrar.');
        }

        $request->validate([
            'monto_contado' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Calcular ventas del día
        $ventas = $this->getVentasDelDia($tienda, $hoy);
        
        // Total esperado en efectivo = apertura + ventas en efectivo
        $totalEsperadoEfectivo = $caja->monto_apertura + $ventas['efectivo'];
        $diferencia = $request->monto_contado - $totalEsperadoEfectivo;

        $caja->update([
            'total_ventas' => $ventas['total'],
            'total_efectivo' => $ventas['efectivo'],
            'total_tarjeta' => $ventas['tarjeta'],
            'total_qr' => $ventas['qr'],
            'total_transferencia' => $ventas['transferencia'],
            'monto_contado' => $request->monto_contado,
            'diferencia' => $diferencia,
            'observaciones' => $request->observaciones,
            'estado' => 'cerrada',
            'cerrada_por' => auth()->id(),
            'hora_cierre' => now(),
        ]);

        $mensaje = 'Caja cerrada exitosamente.';
        if ($diferencia == 0) {
            $mensaje .= ' ¡La caja cuadra perfectamente!';
        } elseif ($diferencia > 0) {
            $mensaje .= ' Hay un sobrante de ' . number_format($diferencia, 2) . ' Bs.';
        } else {
            $mensaje .= ' Hay un faltante de ' . number_format(abs($diferencia), 2) . ' Bs.';
        }

        return back()->with('success', $mensaje);
    }

    /**
     * Historial de cierres
     */
    public function historial(Request $request)
    {
        $tienda = $this->getTienda();
        
        $query = CierreCaja::where('tienda_id', $tienda->id)
            ->orderBy('fecha', 'desc');
        
        // Filtrar por mes si se especifica
        if ($request->filled('mes')) {
            $fecha = Carbon::parse($request->mes . '-01');
            $query->whereMonth('fecha', $fecha->month)
                  ->whereYear('fecha', $fecha->year);
        }
        
        $cierres = $query->paginate(15);
        
        // Estadísticas del mes
        $mesActual = $request->mes ?? now()->format('Y-m');
        $fechaMes = Carbon::parse($mesActual . '-01');
        
        $estadisticasMes = CierreCaja::where('tienda_id', $tienda->id)
            ->where('estado', 'cerrada')
            ->whereMonth('fecha', $fechaMes->month)
            ->whereYear('fecha', $fechaMes->year)
            ->selectRaw('
                SUM(total_ventas) as total_ventas,
                SUM(total_efectivo) as total_efectivo,
                SUM(total_tarjeta) as total_tarjeta,
                SUM(total_qr) as total_qr,
                SUM(total_transferencia) as total_transferencia,
                SUM(diferencia) as total_diferencias,
                COUNT(*) as dias_trabajados
            ')
            ->first();

        return view('tienda-panel.caja.historial', compact('tienda', 'cierres', 'estadisticasMes', 'mesActual'));
    }

    /**
     * Exportar cierre a PDF
     */
    public function exportPdf(CierreCaja $cierre)
    {
        $tienda = $this->getTienda();
        
        if ($cierre->tienda_id !== $tienda->id) {
            abort(403);
        }
        
        // Obtener pedidos del día
        $pedidos = Pedido::whereHas('detalles.producto', function($q) use ($tienda) {
                $q->where('tienda_id', $tienda->id);
            })
            ->whereDate('created_at', $cierre->fecha)
            ->where('estado', '!=', 'cancelado')
            ->with('detalles.producto')
            ->get();
        
        $pdf = \PDF::loadView('tienda-panel.caja.pdf', compact('cierre', 'tienda', 'pedidos'));
        
        return $pdf->download('cierre-caja-' . $cierre->fecha->format('Y-m-d') . '.pdf');
    }

    /**
     * Exportar cierre a Excel
     */
    public function exportExcel(CierreCaja $cierre)
    {
        $tienda = $this->getTienda();
        
        if ($cierre->tienda_id !== $tienda->id) {
            abort(403);
        }

        // Obtener pedidos del día
        $pedidos = Pedido::whereHas('detalles.producto', function($q) use ($tienda) {
                $q->where('tienda_id', $tienda->id);
            })
            ->whereDate('created_at', $cierre->fecha)
            ->where('estado', '!=', 'cancelado')
            ->with('detalles.producto')
            ->get();
        
        $filename = 'cierre-caja-' . $cierre->fecha->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($cierre, $pedidos, $tienda) {
            $file = fopen('php://output', 'w');
            
            // Encabezado
            fputcsv($file, ['CIERRE DE CAJA - ' . $tienda->nombre]);
            fputcsv($file, ['Fecha:', $cierre->fecha->format('d/m/Y')]);
            fputcsv($file, []);
            
            // Resumen
            fputcsv($file, ['RESUMEN']);
            fputcsv($file, ['Monto Apertura:', $cierre->monto_apertura]);
            fputcsv($file, ['Total Ventas:', $cierre->total_ventas]);
            fputcsv($file, ['- Efectivo:', $cierre->total_efectivo]);
            fputcsv($file, ['- Tarjeta:', $cierre->total_tarjeta]);
            fputcsv($file, ['- QR:', $cierre->total_qr]);
            fputcsv($file, ['- Transferencia:', $cierre->total_transferencia]);
            fputcsv($file, ['Monto Contado:', $cierre->monto_contado]);
            fputcsv($file, ['Diferencia:', $cierre->diferencia]);
            fputcsv($file, []);
            
            // Detalle de pedidos
            fputcsv($file, ['DETALLE DE PEDIDOS']);
            fputcsv($file, ['ID', 'Hora', 'Cliente', 'Método Pago', 'Total']);
            
            foreach ($pedidos as $pedido) {
                fputcsv($file, [
                    $pedido->id,
                    $pedido->created_at->format('H:i'),
                    $pedido->user->name ?? 'N/A',
                    ucfirst($pedido->metodo_pago),
                    $pedido->total
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
