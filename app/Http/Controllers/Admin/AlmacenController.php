<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index()
    {
        $almacenes = Almacen::latest()->paginate(15);
        return view('admin.almacenes.index', compact('almacenes'));
    }

    public function create()
    {
        return view('admin.almacenes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_almacen' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:500',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'capacidad' => 'nullable|numeric|min:0',
            'unidad_capacidad' => 'nullable|string',
            'tipo_almacenamiento' => 'nullable|string',
            'responsable' => 'nullable|string|max:255',
            'telefono_contacto' => 'nullable|string|max:20',
            'activo' => 'boolean',
            'es_principal' => 'boolean',
        ]);

        $validated['activo'] = $request->boolean('activo', true);
        $validated['es_principal'] = $request->boolean('es_principal', false);

        if ($validated['es_principal']) {
            Almacen::where('es_principal', true)->update(['es_principal' => false]);
        }

        Almacen::create($validated);

        return redirect()->route('admin.almacenes.index')
            ->with('success', 'Almacén creado exitosamente.');
    }

    public function show(Almacen $almacen)
    {
        return view('admin.almacenes.show', compact('almacen'));
    }

    public function edit(Almacen $almacen)
    {
        return view('admin.almacenes.edit', compact('almacen'));
    }

    public function update(Request $request, Almacen $almacen)
    {
        $validated = $request->validate([
            'nombre_almacen' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:500',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'capacidad' => 'nullable|numeric|min:0',
            'unidad_capacidad' => 'nullable|string',
            'tipo_almacenamiento' => 'nullable|string',
            'responsable' => 'nullable|string|max:255',
            'telefono_contacto' => 'nullable|string|max:20',
            'activo' => 'boolean',
            'es_principal' => 'boolean',
        ]);

        $validated['activo'] = $request->boolean('activo', true);
        $validated['es_principal'] = $request->boolean('es_principal', false);

        if ($validated['es_principal'] && !$almacen->es_principal) {
            Almacen::where('es_principal', true)->update(['es_principal' => false]);
        }

        $almacen->update($validated);

        return redirect()->route('admin.almacenes.index')
            ->with('success', 'Almacén actualizado exitosamente.');
    }

    public function destroy(Almacen $almacen)
    {
        $almacen->delete();

        return redirect()->route('admin.almacenes.index')
            ->with('success', 'Almacén eliminado exitosamente.');
    }
}
