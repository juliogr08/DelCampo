<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Tienda;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function home()
    {
        // Solo productos de tiendas (no productos maestros del admin)
        $productosDestacados = Producto::activos()
            ->deTiendas()
            ->with('tienda')
            ->destacados()
            ->conStock()
            ->take(8)
            ->get();

        $productosRecientes = Producto::activos()
            ->deTiendas()
            ->with('tienda')
            ->conStock()
            ->latest()
            ->take(8)
            ->get();

        $categorias = Producto::CATEGORIAS;
        $tiendas = Tienda::activas()->orderBy('nombre')->get();

        return view('tienda.home', compact('productosDestacados', 'productosRecientes', 'categorias', 'tiendas'));
    }

    public function catalogo(Request $request)
    {
        // Solo productos de tiendas (no admin)
        $query = Producto::activos()->deTiendas()->with('tienda')->conStock();

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('tienda')) {
            $query->where('tienda_id', $request->tienda);
        }

        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->buscar}%")
                  ->orWhere('descripcion', 'like', "%{$request->buscar}%");
            });
        }

        switch ($request->get('orden', 'recientes')) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'nombre':
                $query->orderBy('nombre', 'asc');
                break;
            default:
                $query->latest();
        }

        $productos = $query->paginate(12);
        $categorias = Producto::CATEGORIAS;
        $tiendas = Tienda::activas()->orderBy('nombre')->get();
        $tiendaSeleccionada = $request->filled('tienda') ? Tienda::find($request->tienda) : null;

        return view('tienda.catalogo', compact('productos', 'categorias', 'tiendas', 'tiendaSeleccionada'));
    }

    public function producto(Producto $producto)
    {
        // Solo permitir ver productos de tiendas (no del admin)
        if (!$producto->activo || !$producto->tienda_id) {
            abort(404);
        }

        $producto->load('tienda');

        $relacionados = Producto::activos()
            ->deTiendas()
            ->with('tienda')
            ->conStock()
            ->where('categoria', $producto->categoria)
            ->where('id', '!=', $producto->id)
            ->take(4)
            ->get();

        return view('tienda.producto', compact('producto', 'relacionados'));
    }

    public function perfil($slug)
    {
        $tiendaPerfil = Tienda::where('slug', $slug)
            ->where('estado', 'activa')
            ->firstOrFail();

        $productos = Producto::where('tienda_id', $tiendaPerfil->id)
            ->activos()
            ->conStock()
            ->with('tienda')
            ->paginate(12);

        return view('tienda.perfil-tienda', compact('tiendaPerfil', 'productos'));
    }
}

