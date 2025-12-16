<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class CatalogoAdminController extends Controller
{
    protected function getTienda()
    {
        return auth()->user()->tienda;
    }

    /**
     * Ver catálogo de productos maestros del admin
     */
    public function index()
    {
        $tienda = $this->getTienda();
        
        // Productos maestros del admin (tienda_id = null, aprobados)
        $productosMaestros = Producto::maestros()
            ->orderBy('nombre')
            ->paginate(12);
        
        // Productos que la tienda ya adoptó
        $productosAdoptados = Producto::where('tienda_id', $tienda->id)
            ->whereNotNull('producto_maestro_id')
            ->pluck('producto_maestro_id')
            ->toArray();

        return view('tienda-panel.catalogo-admin.index', compact(
            'tienda', 
            'productosMaestros', 
            'productosAdoptados'
        ));
    }

    /**
     * Adoptar un producto del catálogo del admin
     */
    public function adoptar(Request $request, Producto $producto)
    {
        $tienda = $this->getTienda();
        
        // Verificar que es un producto maestro
        if (!$producto->es_maestro) {
            return back()->with('error', 'Este producto no está disponible para adoptar.');
        }
        
        // Verificar que no esté ya adoptado
        $yaAdoptado = Producto::where('tienda_id', $tienda->id)
            ->where('producto_maestro_id', $producto->id)
            ->exists();
            
        if ($yaAdoptado) {
            return back()->with('error', 'Ya tienes este producto en tu tienda.');
        }

        $request->validate([
            'precio' => 'required|numeric|min:' . ($producto->precio_mayorista ?? 0),
        ], [
            'precio.min' => 'El precio debe ser mayor o igual al precio mayorista (' . ($producto->precio_mayorista ?? 0) . ' Bs)'
        ]);

        // Crear copia del producto para esta tienda
        $productoTienda = Producto::create([
            'tienda_id' => $tienda->id,
            'producto_maestro_id' => $producto->id,
            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion,
            'imagen' => $producto->imagen,
            'categoria' => $producto->categoria,
            'unidad_medida' => $producto->unidad_medida,
            'precio' => $request->precio,
            'precio_mayorista' => $producto->precio_mayorista,
            'stock' => 0, // Empieza sin stock
            'stock_minimo' => $tienda->limite_stock_bajo ?? 5,
            'activo' => false, // Inactivo hasta que tenga stock
            'estado_aprobacion' => 'aprobado',
        ]);

        // Redirigir con parámetro para mostrar modal de solicitar stock
        return redirect()->route('tienda.panel.productos.index')
            ->with('success', 'Producto agregado a tu tienda.')
            ->with('producto_adoptado_id', $productoTienda->id);
    }
}
