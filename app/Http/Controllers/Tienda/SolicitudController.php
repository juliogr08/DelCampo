<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\SolicitudReposicion;
use App\Models\Producto;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    protected function getTienda()
    {
        return auth()->user()->tienda;
    }

    public function index()
    {
        $tienda = $this->getTienda();
        $solicitudes = SolicitudReposicion::where('tienda_solicitante_id', $tienda->id)
            ->with('producto')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tienda-panel.solicitudes.index', compact('solicitudes', 'tienda'));
    }

    public function create(Request $request)
    {
        $tienda = $this->getTienda();
        
        // Include both admin products and store's own products that need stock
        $productosAdmin = Producto::where(function($query) use ($tienda) {
                // Productos maestros del admin
                $query->whereNull('tienda_id')
                      ->where('estado_aprobacion', 'aprobado')
                      ->where('activo', true);
            })
            ->orWhere(function($query) use ($tienda) {
                // Productos propios de la tienda que necesitan stock
                $query->where('tienda_id', $tienda->id);
            })
            ->orderBy('nombre')
            ->get();
        
        // Pre-select product if coming from adoption modal
        $productoIdSeleccionado = $request->query('producto_id');

        return view('tienda-panel.solicitudes.create', compact('tienda', 'productosAdmin', 'productoIdSeleccionado'));
    }

    public function store(Request $request)
    {
        $tienda = $this->getTienda();

        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad_solicitada' => 'required|integer|min:1',
            'notas' => 'nullable|string|max:500',
        ]);

        $producto = Producto::find($request->producto_id);
        
        // Get the store's main warehouse
        $almacenPrincipal = $tienda->almacenes()->where('es_sede_principal', true)->first();
        
        if (!$almacenPrincipal) {
            $almacenPrincipal = $tienda->almacenes()->first();
        }

        SolicitudReposicion::create([
            'tienda_solicitante_id' => $tienda->id,
            'producto_id' => $request->producto_id,
            'almacen_id' => $almacenPrincipal ? $almacenPrincipal->id : null,
            'cantidad_solicitada' => $request->cantidad_solicitada,
            'monto_total' => $producto->precio * $request->cantidad_solicitada,
            'estado' => 'pendiente',
            'tipo' => 'tienda_a_admin',
            'notas' => $request->notas,
        ]);

        if ($tienda->estado === 'pendiente') {
            $tienda->update(['estado' => 'activa']);
        }

        return redirect()->route('tienda.panel.solicitudes.index')
            ->with('success', 'Solicitud enviada al administrador. Tu tienda ha sido activada.');
    }

    public function show(SolicitudReposicion $solicitud)
    {
        $this->authorizeSolicitud($solicitud);
        return view('tienda-panel.solicitudes.show', compact('solicitud'));
    }

    public function destroy(SolicitudReposicion $solicitud)
    {
        $this->authorizeSolicitud($solicitud);

        if ($solicitud->estado !== 'pendiente') {
            return back()->with('error', 'Solo puedes cancelar solicitudes pendientes.');
        }

        $solicitud->delete();

        return redirect()->route('tienda.panel.solicitudes.index')
            ->with('success', 'Solicitud cancelada.');
    }

    protected function authorizeSolicitud(SolicitudReposicion $solicitud)
    {
        $tienda = $this->getTienda();
        if ($solicitud->tienda_solicitante_id !== $tienda->id) {
            abort(403, 'No tienes permiso para acceder a esta solicitud.');
        }
    }
}
