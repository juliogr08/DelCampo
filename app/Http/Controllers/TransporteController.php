<?php

namespace App\Http\Controllers;

use App\Models\Transporte;
use Illuminate\Http\Request;

class TransporteController extends Controller
{
    public function index()
    {
        $transportes = Transporte::all();
        return view('transportes.index', compact('transportes'));
    }

    public function create()
    {
        return view('transportes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'placa_vehiculo' => 'required|string|max:10|unique:transportes',
            'conductor' => 'required|string|max:255|regex:/^[A-Za-záéíóúñÑ\s]+$/',
            'capacidad_carga' => 'required|numeric|min:0',
            'unidad_carga' => 'required|in:kg,ton,m3',
            'tipo_temperatura' => 'required|in:ambiente,refrigerado,congelado',
            'temperatura_minima' => 'nullable|numeric',
            'temperatura_maxima' => 'nullable|numeric',
            'estado' => 'required|in:disponible,en_mantenimiento,en_ruta',
            'telefono_conductor' => 'required|string|size:8|regex:/^[0-9]+$/'
        ]);

        $data = $request->all();
        $data = $this->asignarTemperaturas($data);

        Transporte::create($data);

        return redirect()->route('transportes.index')
            ->with('success', 'Transporte creado exitosamente.');
    }

    public function show(Transporte $transporte)
    {
        return view('transportes.show', compact('transporte'));
    }

    public function edit(Transporte $transporte)
    {
        return view('transportes.edit', compact('transporte'));
    }

    public function update(Request $request, Transporte $transporte)
    {
        $request->validate([
            'placa_vehiculo' => 'required|string|max:10|unique:transportes,placa_vehiculo,' . $transporte->id,
            'conductor' => 'required|string|max:255|regex:/^[A-Za-záéíóúñÑ\s]+$/',
            'capacidad_carga' => 'required|numeric|min:0',
            'unidad_carga' => 'required|in:kg,ton,m3',
            'tipo_temperatura' => 'required|in:ambiente,refrigerado,congelado',
            'temperatura_minima' => 'nullable|numeric',
            'temperatura_maxima' => 'nullable|numeric',
            'estado' => 'required|in:disponible,en_mantenimiento,en_ruta',
            'telefono_conductor' => 'required|string|size:8|regex:/^[0-9]+$/'
        ]);

        $data = $request->all();
        $data = $this->asignarTemperaturas($data);

        $transporte->update($data);

        return redirect()->route('transportes.index')
            ->with('success', 'Transporte actualizado exitosamente.');
    }

    public function destroy(Transporte $transporte)
    {
        $transporte->delete();

        return redirect()->route('transportes.index')
            ->with('success', 'Transporte eliminado exitosamente.');
    }

    private function asignarTemperaturas($data)
    {
        $temperaturas = [
            'ambiente' => ['min' => 15, 'max' => 25],
            'refrigerado' => ['min' => 2, 'max' => 8],
            'congelado' => ['min' => -18, 'max' => -18]
        ];

        if (isset($data['tipo_temperatura']) && array_key_exists($data['tipo_temperatura'], $temperaturas)) {
            $data['temperatura_minima'] = $temperaturas[$data['tipo_temperatura']]['min'];
            $data['temperatura_maxima'] = $temperaturas[$data['tipo_temperatura']]['max'];
        }

        return $data;
    }
}