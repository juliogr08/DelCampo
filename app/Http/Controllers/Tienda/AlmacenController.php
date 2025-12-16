<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    protected function getTienda()
    {
        return auth()->user()->tienda;
    }

    public function index()
    {
        $tienda = $this->getTienda();
        $almacenes = Almacen::where('tienda_id', $tienda->id)
            ->orderBy('es_sede_principal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tienda-panel.almacenes.index', compact('almacenes', 'tienda'));
    }

    public function create()
    {
        $tienda = $this->getTienda();
        $tiposAlmacenamiento = Almacen::TIPOS_ALMACENAMIENTO;

        return view('tienda-panel.almacenes.create', compact('tienda', 'tiposAlmacenamiento'));
    }

    public function store(Request $request)
    {
        $tienda = $this->getTienda();

        $request->validate([
            'nombre_almacen' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:500',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'tipo_almacenamiento' => 'required|string|in:' . implode(',', array_keys(Almacen::TIPOS_ALMACENAMIENTO)),
            'capacidad' => 'nullable|numeric|min:0',
            'responsable' => 'nullable|string|max:255',
            'telefono_contacto' => 'nullable|string|max:20',
        ]);

        Almacen::create([
            'tienda_id' => $tienda->id,
            'nombre_almacen' => $request->nombre_almacen,
            'ubicacion' => $request->ubicacion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'tipo_almacenamiento' => $request->tipo_almacenamiento,
            'capacidad' => $request->capacidad ?? 100,
            'unidad_capacidad' => 'm2',
            'responsable' => $request->responsable,
            'telefono_contacto' => $request->telefono_contacto,
            'activo' => true,
            'es_principal' => false,
            'es_sede_principal' => false,
        ]);

        return redirect()->route('tienda.panel.almacenes.index')
            ->with('success', 'Almacén creado exitosamente.');
    }

    public function edit(Almacen $almacen)
    {
        $this->authorizeAlmacen($almacen);
        $tienda = $this->getTienda();
        $tiposAlmacenamiento = Almacen::TIPOS_ALMACENAMIENTO;

        return view('tienda-panel.almacenes.edit', compact('almacen', 'tienda', 'tiposAlmacenamiento'));
    }

    public function update(Request $request, Almacen $almacen)
    {
        $this->authorizeAlmacen($almacen);

        $request->validate([
            'nombre_almacen' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:500',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'tipo_almacenamiento' => 'required|string|in:' . implode(',', array_keys(Almacen::TIPOS_ALMACENAMIENTO)),
            'capacidad' => 'nullable|numeric|min:0',
            'responsable' => 'nullable|string|max:255',
            'telefono_contacto' => 'nullable|string|max:20',
            'activo' => 'nullable|boolean',
        ]);

        $almacen->update([
            'nombre_almacen' => $request->nombre_almacen,
            'ubicacion' => $request->ubicacion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'tipo_almacenamiento' => $request->tipo_almacenamiento,
            'capacidad' => $request->capacidad,
            'responsable' => $request->responsable,
            'telefono_contacto' => $request->telefono_contacto,
            'activo' => $request->has('activo'),
        ]);

        return redirect()->route('tienda.panel.almacenes.index')
            ->with('success', 'Almacén actualizado exitosamente.');
    }

    public function destroy(Almacen $almacen)
    {
        $this->authorizeAlmacen($almacen);

        if ($almacen->es_sede_principal) {
            return back()->with('error', 'No puedes eliminar la sede principal.');
        }

        $almacen->delete();

        return redirect()->route('tienda.panel.almacenes.index')
            ->with('success', 'Almacén eliminado exitosamente.');
    }

    protected function authorizeAlmacen(Almacen $almacen)
    {
        $tienda = $this->getTienda();
        if ($almacen->tienda_id !== $tienda->id) {
            abort(403, 'No tienes permiso para acceder a este almacén.');
        }
    }
}
