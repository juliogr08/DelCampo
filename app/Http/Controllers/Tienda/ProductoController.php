<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    protected function getTienda()
    {
        return auth()->user()->tienda;
    }

    public function index()
    {
        $tienda = $this->getTienda();
        $productos = Producto::where('tienda_id', $tienda->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tienda-panel.productos.index', compact('productos', 'tienda'));
    }

    public function create()
    {
        $tienda = $this->getTienda();
        $categorias = Producto::CATEGORIAS;
        $unidades = Producto::UNIDADES_MEDIDA;

        return view('tienda-panel.productos.create', compact('tienda', 'categorias', 'unidades'));
    }

    public function store(Request $request)
    {
        $tienda = $this->getTienda();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'precio' => 'required|numeric|min:0',
            // Stock removed - always starts at 0
            'categoria' => 'required|string|in:' . implode(',', array_keys(Producto::CATEGORIAS)),
            'unidad_medida' => 'required|string|in:' . implode(',', array_keys(Producto::UNIDADES_MEDIDA)),
        ]);

        // Handle image with native PHP to avoid MIME detection issues
        $imagenPath = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['imagen']['tmp_name'];
            $originalName = $_FILES['imagen']['name'];
            $size = $_FILES['imagen']['size'];
            
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($extension, $allowedExtensions) && $size <= 2048000) {
                $filename = 'producto_' . time() . '_' . uniqid() . '.' . $extension;
                $destination = storage_path('app/public/productos/' . $filename);
                
                if (!file_exists(storage_path('app/public/productos'))) {
                    mkdir(storage_path('app/public/productos'), 0755, true);
                }
                
                if (move_uploaded_file($tmpName, $destination)) {
                    $imagenPath = 'productos/' . $filename;
                }
            }
        }

        // Stock always starts at 0, product is inactive until stock is added via approved request
        Producto::create([
            'tienda_id' => $tienda->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => 0, // Always 0 - stock comes from approved requests
            'stock_minimo' => $tienda->limite_stock_bajo ?? 5,
            'categoria' => $request->categoria,
            'unidad_medida' => $request->unidad_medida,
            'imagen' => $imagenPath,
            'activo' => false, // Always inactive until stock is added
        ]);

        return redirect()->route('tienda.panel.productos.index')
            ->with('success', 'Producto registrado. Para activarlo, solicita stock al administrador desde "Solicitar Stock".');
    }

    public function show(Producto $producto)
    {
        $this->authorizeProducto($producto);
        return view('tienda-panel.productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $this->authorizeProducto($producto);
        $tienda = $this->getTienda();
        $categorias = Producto::CATEGORIAS;
        $unidades = Producto::UNIDADES_MEDIDA;

        return view('tienda-panel.productos.edit', compact('producto', 'tienda', 'categorias', 'unidades'));
    }

    public function update(Request $request, Producto $producto)
    {
        $this->authorizeProducto($producto);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria' => 'required|string|in:' . implode(',', array_keys(Producto::CATEGORIAS)),
            'unidad_medida' => 'required|string|in:' . implode(',', array_keys(Producto::UNIDADES_MEDIDA)),
            // Image validation removed to avoid MIME detection
            'activo' => 'nullable|boolean',
        ]);

        // Handle image with native PHP
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['imagen']['tmp_name'];
            $originalName = $_FILES['imagen']['name'];
            $size = $_FILES['imagen']['size'];
            
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($extension, $allowedExtensions) && $size <= 2048000) {
                // Delete old image
                if ($producto->imagen) {
                    Storage::disk('public')->delete($producto->imagen);
                }
                
                $filename = 'producto_' . time() . '_' . uniqid() . '.' . $extension;
                $destination = storage_path('app/public/productos/' . $filename);
                
                if (!file_exists(storage_path('app/public/productos'))) {
                    mkdir(storage_path('app/public/productos'), 0755, true);
                }
                
                if (move_uploaded_file($tmpName, $destination)) {
                    $producto->imagen = 'productos/' . $filename;
                }
            }
        }

        // Business logic: Can't be active without stock
        $esActivo = $request->has('activo');
        if ($request->stock == 0) {
            $esActivo = false;
        }

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria' => $request->categoria,
            'unidad_medida' => $request->unidad_medida,
            'activo' => $esActivo,
        ]);

        $mensaje = 'Producto actualizado exitosamente.';
        if ($request->stock == 0 && $request->has('activo')) {
            $mensaje .= ' El producto permanece inactivo porque no tiene stock.';
        }

        return redirect()->route('tienda.panel.productos.index')
            ->with('success', $mensaje);
    }

    public function destroy(Producto $producto)
    {
        $this->authorizeProducto($producto);

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('tienda.panel.productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    protected function authorizeProducto(Producto $producto)
    {
        $tienda = $this->getTienda();
        if ($producto->tienda_id !== $tienda->id) {
            abort(403, 'No tienes permiso para acceder a este producto.');
        }
    }
}
