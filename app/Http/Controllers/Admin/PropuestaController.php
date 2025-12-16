<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class PropuestaController extends Controller
{
    /**
     * Lista de productos propuestos pendientes de aprobación
     */
    public function index(Request $request)
    {
        $query = Producto::whereNull('tienda_id')
            ->whereNotNull('propuesto_por_tienda_id')
            ->with('propuestoPorTienda');

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado_aprobacion', $request->estado);
        } else {
            $query->where('estado_aprobacion', 'pendiente');
        }

        $propuestas = $query->latest()->paginate(15);
        
        // Contar pendientes para badge
        $pendientesCount = Producto::whereNull('tienda_id')
            ->whereNotNull('propuesto_por_tienda_id')
            ->where('estado_aprobacion', 'pendiente')
            ->count();

        return view('admin.propuestas.index', compact('propuestas', 'pendientesCount'));
    }

    /**
     * Ver detalle de una propuesta
     */
    public function show(Producto $producto)
    {
        if (!$producto->propuesto_por_tienda_id) {
            return redirect()->route('admin.propuestas.index')
                ->with('error', 'Este producto no es una propuesta.');
        }

        $producto->load('propuestoPorTienda');
        
        return view('admin.propuestas.show', compact('producto'));
    }

    /**
     * Aprobar propuesta - el admin debe establecer precio mayorista
     */
    public function aprobar(Request $request, Producto $producto)
    {
        if ($producto->estado_aprobacion !== 'pendiente') {
            return redirect()->route('admin.propuestas.index')
                ->with('error', 'Esta propuesta ya fue procesada.');
        }

        $request->validate([
            'precio_mayorista' => 'required|numeric|min:0.01',
        ], [
            'precio_mayorista.required' => 'Debes establecer el precio mayorista.',
            'precio_mayorista.min' => 'El precio mayorista debe ser mayor a cero.',
        ]);

        $producto->update([
            'estado_aprobacion' => 'aprobado',
            'precio_mayorista' => $request->precio_mayorista,
            'precio' => $request->precio_mayorista, // Precio base igual al mayorista
            'activo' => true, // Producto maestro activo
        ]);

        return redirect()->route('admin.propuestas.index')
            ->with('success', "Producto '{$producto->nombre}' aprobado. Ya está disponible para todas las tiendas.");
    }

    /**
     * Rechazar propuesta con motivo
     */
    public function rechazar(Request $request, Producto $producto)
    {
        if ($producto->estado_aprobacion !== 'pendiente') {
            return redirect()->route('admin.propuestas.index')
                ->with('error', 'Esta propuesta ya fue procesada.');
        }

        $request->validate([
            'motivo_rechazo' => 'required|string|max:500',
        ], [
            'motivo_rechazo.required' => 'Debes indicar el motivo del rechazo.',
        ]);

        $producto->update([
            'estado_aprobacion' => 'rechazado',
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        return redirect()->route('admin.propuestas.index')
            ->with('success', "Propuesta '{$producto->nombre}' rechazada.");
    }
}
