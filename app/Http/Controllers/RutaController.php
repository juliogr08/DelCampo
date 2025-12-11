<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\Transporte;
use App\Models\Almacen;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        $rutas = Ruta::with('transporte')->get();
        return view('rutas.index', compact('rutas'));
    }

    public function create()
    {
        $transportes = Transporte::where('estado', 'disponible')->get();
        $almacenes = Almacen::where('activo', true)->get();
        return view('rutas.create', compact('transportes', 'almacenes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transporte_id' => 'required|exists:transportes,id',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'fecha_salida' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $fechaSalida = \Carbon\Carbon::parse($value);
                    $ahora = \Carbon\Carbon::now();
                    if ($fechaSalida->lt($ahora->subMinutes(5))) {
                        $fail('La fecha de salida debe ser igual o posterior a la fecha y hora actual.');
                    }
                },
            ],
            'fecha_estimada_llegada' => 'required|date|after:fecha_salida',
            'estado_envio' => 'required|in:pendiente,en_camino,entregado,cancelado',
            'temperatura_registrada' => 'required|in:ambiente,refrigerado,congelado',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $data = $request->all();
        $data['temperatura_registrada'] = $this->getTemperaturaValor($request->temperatura_registrada);

        if ($request->estado_envio === 'en_camino') {
            Transporte::where('id', $request->transporte_id)->update(['estado' => 'en_ruta']);
        }

        Ruta::create($data);

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta creada exitosamente.');
    }

    private function getTemperaturaValor($tipoTemperatura)
    {
        $temperaturas = [
            'ambiente' => 20.0,
            'refrigerado' => 5.0,
            'congelado' => -18.0
        ];
        
        return $temperaturas[$tipoTemperatura] ?? 20.0;
    }

    public function edit(Ruta $ruta)
    {
        $transportes = Transporte::whereIn('estado', ['disponible', 'en_ruta'])->get();
        $almacenes = Almacen::where('activo', true)->get();
        return view('rutas.edit', compact('ruta', 'transportes', 'almacenes'));
    }

    public function update(Request $request, Ruta $ruta)
    {
        $request->validate([
            'transporte_id' => 'required|exists:transportes,id',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'fecha_salida' => 'required|date',
            'fecha_estimada_llegada' => 'required|date|after:fecha_salida',
            'estado_envio' => 'required|in:pendiente,en_camino,entregado,cancelado',
            'temperatura_registrada' => 'required|in:ambiente,refrigerado,congelado',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $data = $request->all();
        $data['temperatura_registrada'] = $this->getTemperaturaValor($request->temperatura_registrada);

        $this->actualizarEstadoTransporte($ruta->transporte_id, $request->estado_envio);

        $ruta->update($data);

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta actualizada exitosamente.');
    }

    public function destroy(Ruta $ruta)
    {
        if ($ruta->estado_envio === 'en_camino') {
            Transporte::where('id', $ruta->transporte_id)->update(['estado' => 'disponible']);
        }

        $ruta->delete();

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta eliminada exitosamente.');
    }

    private function actualizarEstadoTransporte($transporteId, $estadoEnvio)
    {
        $nuevoEstado = 'disponible';
        
        if ($estadoEnvio === 'en_camino') {
            $nuevoEstado = 'en_ruta';
        } elseif ($estadoEnvio === 'pendiente') {
            $nuevoEstado = 'disponible';
        }

        Transporte::where('id', $transporteId)->update(['estado' => $nuevoEstado]);
    }
}