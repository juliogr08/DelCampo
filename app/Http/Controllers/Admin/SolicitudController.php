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
        $query = SolicitudReposicion::with(['almacen', 'producto']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $solicitudes = $query->latest()->paginate(15);

        return view('admin.solicitudes.index', compact('solicitudes'));
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

        $solicitud->producto->increment('stock', $request->cantidad_recibida);

        return redirect()->route('admin.solicitudes.index')
            ->with('success', "Recibidas {$request->cantidad_recibida} unidades. Stock actualizado.");
    }
}
