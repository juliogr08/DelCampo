<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Producto;
use App\Models\SolicitudReposicion;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $query = SolicitudReposicion::with(['almacen', 'producto', 'tiendaSolicitante']);

        // Filtrar por tipo (tienda_a_admin o admin_a_productor)
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $solicitudes = $query->latest()->paginate(15);
        $tipoActual = $request->get('tipo', 'admin_a_productor');

        return view('admin.solicitudes.index', compact('solicitudes', 'tipoActual'));
    }

    public function create()
    {
        $almacenes = Almacen::activos()->get();
        $productos = Producto::activos()->get();
        
        $stockBajo = Producto::stockBajo()->get();

        return view('admin.solicitudes.create', compact('almacenes', 'productos', 'stockBajo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'almacen_id' => 'required|exists:almacenes,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad_solicitada' => 'required|integer|min:1',
            'notas' => 'nullable|string|max:500',
        ]);

        $validated['estado'] = 'pendiente';

        SolicitudReposicion::create($validated);

        return redirect()->route('admin.solicitudes.index')
            ->with('success', 'Solicitud de reposición creada. Pendiente de envío al almacén externo.');
    }

    public function show(SolicitudReposicion $solicitud)
    {
        $solicitud->load(['almacen', 'producto']);
        return view('admin.solicitudes.show', compact('solicitud'));
    }

    public function edit(SolicitudReposicion $solicitud)
    {
        if (!in_array($solicitud->estado, ['pendiente'])) {
            return redirect()->route('admin.solicitudes.index')
                ->with('error', 'Solo se pueden editar solicitudes pendientes.');
        }

        $almacenes = Almacen::activos()->get();
        $productos = Producto::activos()->get();

        return view('admin.solicitudes.edit', compact('solicitud', 'almacenes', 'productos'));
    }

    public function update(Request $request, SolicitudReposicion $solicitud)
    {
        if (!in_array($solicitud->estado, ['pendiente'])) {
            return redirect()->route('admin.solicitudes.index')
                ->with('error', 'Solo se pueden editar solicitudes pendientes.');
        }

        $validated = $request->validate([
            'cantidad_solicitada' => 'required|integer|min:1',
            'notas' => 'nullable|string|max:500',
        ]);

        $solicitud->update($validated);

        return redirect()->route('admin.solicitudes.index')
            ->with('success', 'Solicitud actualizada.');
    }

    public function destroy(SolicitudReposicion $solicitud)
    {
        if (!in_array($solicitud->estado, ['pendiente'])) {
            return redirect()->route('admin.solicitudes.index')
                ->with('error', 'Solo se pueden eliminar solicitudes pendientes.');
        }

        $solicitud->delete();

        return redirect()->route('admin.solicitudes.index')
            ->with('success', 'Solicitud eliminada.');
    }

    public function recibir(Request $request, SolicitudReposicion $solicitud)
    {
        $request->validate([
            'cantidad_recibida' => 'required|integer|min:1'
        ]);

        $solicitud->update([
            'estado' => 'recibida',
            'cantidad_recibida' => $request->cantidad_recibida,
            'fecha_respuesta' => now()
        ]);

        // Add stock to the product
        $producto = $solicitud->producto;
        $producto->increment('stock', $request->cantidad_recibida);
        
        // If product belongs to a store and was inactive, activate it now that it has stock
        if ($producto->tienda_id && !$producto->activo && $producto->stock > 0) {
            $producto->update(['activo' => true]);
        }

        $mensaje = "Recibidas {$request->cantidad_recibida} unidades. Stock actualizado.";
        if ($producto->tienda_id && $producto->activo) {
            $mensaje .= " El producto de la tienda fue activado automáticamente.";
        }

        return redirect()->route('admin.solicitudes.index')
            ->with('success', $mensaje);
    }
}

