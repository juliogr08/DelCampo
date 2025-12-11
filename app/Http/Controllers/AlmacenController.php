<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index()
    {
        $almacenes = Almacen::all();
        return view('almacenes.index', compact('almacenes'));
    }

    public function create()
    {
        return view('almacenes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_almacen' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:500',
            'capacidad' => 'required|numeric|min:0',
            'unidad_capacidad' => 'required|in:m2,hectareas',
            'tipo_almacenamiento' => 'required|in:ambiente,refrigerado,congelado',
            'responsable' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|size:8|regex:/^[0-9]+$/',
            'activo' => 'boolean'
        ]);

        $data = $request->all();
        $data['temperatura_actual'] = $this->getTemperaturaPorTipo($request->tipo_almacenamiento);
        
        $data['activo'] = $request->has('activo');
        
        Almacen::create($data);

        return redirect()->route('almacenes.index')
            ->with('success', 'Almacén creado exitosamente.');
    }

    private function getTemperaturaPorTipo($tipo)
    {
        $temperaturas = [
            'ambiente' => 20.0,
            'refrigerado' => 5.0,
            'congelado' => -18.0
        ];
        
        return $temperaturas[$tipo] ?? 20.0;
    }

    public function show(Almacen $almacen)
    {
        return view('almacenes.show', compact('almacen'));
    }

    public function edit(Almacen $almacen)
    {
        return view('almacenes.edit', compact('almacen'));
    }

    public function update(Request $request, Almacen $almacen)
    {
        $request->validate([
            'nombre_almacen' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:500',
            'capacidad' => 'required|numeric|min:0',
            'unidad_capacidad' => 'required|in:m2,hectareas',
            'tipo_almacenamiento' => 'required|in:ambiente,refrigerado,congelado',
            'responsable' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|size:8|regex:/^[0-9]+$/',
            'activo' => 'boolean'
        ]);

        $data = $request->all();
        $data['temperatura_actual'] = $this->getTemperaturaPorTipo($request->tipo_almacenamiento);
        
        $data['activo'] = $request->has('activo');
        
        $almacen->update($data);

        return redirect()->route('almacenes.index')
            ->with('success', 'Almacén actualizado exitosamente.');
    }

    public function destroy(Almacen $almacen)
    {
        $almacen->delete();

        return redirect()->route('almacenes.index')
            ->with('success', 'Almacén eliminado exitosamente.');
    }
}