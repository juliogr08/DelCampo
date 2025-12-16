<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $tienda = auth()->user()->tienda;
        $limitesStock = Tienda::LIMITES_STOCK;

        return view('tienda-panel.configuracion', compact('tienda', 'limitesStock'));
    }

    public function update(Request $request)
    {
        $tienda = auth()->user()->tienda;

        $request->validate([
            'nombre' => 'required|string|max:100|unique:tiendas,nombre,' . $tienda->id,
            'descripcion' => 'nullable|string|max:280',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:500',
            'limite_stock_bajo' => 'required|integer|in:' . implode(',', Tienda::LIMITES_STOCK),
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($tienda->logo_path) {
                Storage::disk('public')->delete($tienda->logo_path);
            }
            $tienda->logo_path = $request->file('logo')->store('tiendas/logos', 'public');
        }

        $tienda->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'limite_stock_bajo' => $request->limite_stock_bajo,
        ]);

        return redirect()->route('tienda.panel.configuracion')
            ->with('success', 'Configuración actualizada exitosamente.');
    }
}
