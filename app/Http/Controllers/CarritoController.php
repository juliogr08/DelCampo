<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session()->get('carrito', []);
        $productos = [];
        $total = 0;

        foreach ($carrito as $productoId => $cantidad) {
            $producto = Producto::find($productoId);
            if ($producto) {
                $productos[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'subtotal' => $producto->precio * $cantidad
                ];
                $total += $producto->precio * $cantidad;
            }
        }

        return view('tienda.carrito', compact('productos', 'total'));
    }

    public function agregar(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'integer|min:1|max:100'
        ]);

        $cantidad = $request->get('cantidad', 1);
        $carrito = session()->get('carrito', []);

        // Verificar stock disponible
        $cantidadEnCarrito = $carrito[$producto->id] ?? 0;
        $nuevaCantidad = $cantidadEnCarrito + $cantidad;

        if ($nuevaCantidad > $producto->stock) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente. Disponible: ' . $producto->stock
                ], 400);
            }
            return back()->with('error', 'Stock insuficiente. Disponible: ' . $producto->stock);
        }

        $carrito[$producto->id] = $nuevaCantidad;
        session()->put('carrito', $carrito);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto agregado al carrito',
                'carrito_count' => array_sum($carrito)
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito');
    }

    public function actualizar(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:0|max:100'
        ]);

        $carrito = session()->get('carrito', []);

        if ($request->cantidad == 0) {
            unset($carrito[$producto->id]);
        } else {
            if ($request->cantidad > $producto->stock) {
                return back()->with('error', 'Stock insuficiente');
            }
            $carrito[$producto->id] = $request->cantidad;
        }

        session()->put('carrito', $carrito);

        return back()->with('success', 'Carrito actualizado');
    }

    public function eliminar(Producto $producto)
    {
        $carrito = session()->get('carrito', []);
        unset($carrito[$producto->id]);
        session()->put('carrito', $carrito);

        return back()->with('success', 'Producto eliminado del carrito');
    }

    public function vaciar()
    {
        session()->forget('carrito');
        return back()->with('success', 'Carrito vaciado');
    }
}
