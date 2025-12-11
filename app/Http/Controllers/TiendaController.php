<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function home()
    {
        $productosDestacados = Producto::activos()
            ->destacados()
            ->conStock()
            ->take(8)
            ->get();

        $productosRecientes = Producto::activos()
            ->conStock()
            ->latest()
            ->take(8)
            ->get();

        $categorias = Producto::CATEGORIAS;

        return view('tienda.home', compact('productosDestacados', 'productosRecientes', 'categorias'));
    }

    public function catalogo(Request $request)
    {
        $query = Producto::activos()->conStock();

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
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

        return view('tienda.catalogo', compact('productos', 'categorias'));
    }

    public function producto(Producto $producto)
    {
        if (!$producto->activo) {
            abort(404);
        }

        $relacionados = Producto::activos()
            ->conStock()
            ->where('categoria', $producto->categoria)
            ->where('id', '!=', $producto->id)
            ->take(4)
            ->get();

        return view('tienda.producto', compact('producto', 'relacionados'));
    }
}
