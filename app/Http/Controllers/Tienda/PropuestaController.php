<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class PropuestaController extends Controller
{
    protected function getTienda()
    {
        return auth()->user()->tienda;
    }

    /**
     * Lista de propuestas de la tienda
     */
    public function index()
    {
        $tienda = $this->getTienda();
        
        // Productos propuestos por esta tienda (pendientes de aprobación)
        $propuestas = Producto::whereNull('tienda_id')
            ->where('propuesto_por_tienda_id', $tienda->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tienda-panel.propuestas.index', compact('tienda', 'propuestas'));
    }

    /**
     * Formulario para proponer un nuevo producto
     */
    public function create()
    {
        $tienda = $this->getTienda();
        $categorias = Producto::CATEGORIAS;
        $unidades = Producto::UNIDADES_MEDIDA;

        return view('tienda-panel.propuestas.create', compact('tienda', 'categorias', 'unidades'));
    }

    /**
     * Guardar propuesta de nuevo producto
     */
    public function store(Request $request)
    {
        $tienda = $this->getTienda();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'categoria' => 'required|string|in:' . implode(',', array_keys(Producto::CATEGORIAS)),
            'unidad_medida' => 'required|string|in:' . implode(',', array_keys(Producto::UNIDADES_MEDIDA)),
            'precio_sugerido' => 'nullable|numeric|min:0',
        ]);

        // Handle image with native PHP
        $imagenPath = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['imagen']['tmp_name'];
            $originalName = $_FILES['imagen']['name'];
            $size = $_FILES['imagen']['size'];
            
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($extension, $allowedExtensions) && $size <= 2048000) {
                $filename = 'propuesta_' . time() . '_' . uniqid() . '.' . $extension;
                $destination = storage_path('app/public/productos/' . $filename);
                
                if (!file_exists(storage_path('app/public/productos'))) {
                    mkdir(storage_path('app/public/productos'), 0755, true);
                }
                
                if (move_uploaded_file($tmpName, $destination)) {
                    $imagenPath = 'productos/' . $filename;
                }
            }
        }

        // Crear producto como propuesta (sin tienda_id, pendiente de aprobación)
        Producto::create([
            'tienda_id' => null, // Es un producto maestro propuesto
            'propuesto_por_tienda_id' => $tienda->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'imagen' => $imagenPath,
            'categoria' => $request->categoria,
            'unidad_medida' => $request->unidad_medida,
            'precio' => 0,
            'precio_sugerido' => $request->precio_sugerido,
            'stock' => 0,
            'stock_minimo' => 5,
            'activo' => false,
            'estado_aprobacion' => 'pendiente',
        ]);

        return redirect()->route('tienda.panel.mis-propuestas')
            ->with('success', 'Producto propuesto enviado al administrador para su aprobación. Te notificaremos cuando sea revisado.');
    }
}
