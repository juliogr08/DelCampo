<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->buscar}%")
                  ->orWhere('codigo_barras', 'like', "%{$request->buscar}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('estado')) {
            $query->where('activo', $request->estado === 'activo');
        }

        $productos = $query->latest()->paginate(15);

        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        return view('admin.productos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'codigo_barras' => 'required|string|unique:productos',
            'precio' => 'required|numeric|min:0',
            'peso' => 'nullable|numeric|min:0',
            'unidad_medida' => 'required|string',
            'categoria' => 'required|string',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'activo' => 'boolean',
            'destacado' => 'boolean',
        ]);

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $this->subirImagen($request->file('imagen'));
            if ($imagenPath === false) {
                return back()->withErrors(['imagen' => 'Error al subir la imagen. Verifica que sea un archivo válido.'])->withInput();
            }
        }

        $validated['activo'] = $request->boolean('activo', true);
        $validated['destacado'] = $request->boolean('destacado', false);
        $validated['lote'] = 'LOTE-' . date('Ymd');
        $validated['fecha_vencimiento'] = now()->addYear();
        
        if ($imagenPath) {
            $validated['imagen'] = $imagenPath;
        }

        Producto::create($validated);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show(Producto $producto)
    {
        return view('admin.productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'codigo_barras' => 'required|string|unique:productos,codigo_barras,' . $producto->id,
            'precio' => 'required|numeric|min:0',
            'peso' => 'nullable|numeric|min:0',
            'unidad_medida' => 'required|string',
            'categoria' => 'required|string',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'activo' => 'boolean',
            'destacado' => 'boolean',
        ]);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                $rutaAnterior = storage_path('app/public/' . $producto->imagen);
                if (File::exists($rutaAnterior)) {
                    File::delete($rutaAnterior);
                }
            }
            
            $imagenPath = $this->subirImagen($request->file('imagen'), $producto->id);
            if ($imagenPath === false) {
                return back()->withErrors(['imagen' => 'Error al subir la imagen. Verifica que sea un archivo válido.'])->withInput();
            }
            $validated['imagen'] = $imagenPath;
        }

        $validated['activo'] = $request->boolean('activo', true);
        $validated['destacado'] = $request->boolean('destacado', false);

        $producto->update($validated);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->imagen) {
            $ruta = storage_path('app/public/' . $producto->imagen);
            if (File::exists($ruta)) {
                File::delete($ruta);
            }
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    private function subirImagen($file, $productoId = null)
    {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $extensionesPermitidas)) {
            return false;
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            return false;
        }

        try {
            $nombreArchivo = 'prod_' . ($productoId ?? time()) . '_' . time() . '.' . $extension;
            
            $directorio = storage_path('app/public/productos');
            if (!File::isDirectory($directorio)) {
                File::makeDirectory($directorio, 0755, true);
            }
            
            $file->move($directorio, $nombreArchivo);
            
            return 'productos/' . $nombreArchivo;
        } catch (\Exception $e) {
            return false;
        }
    }
}
