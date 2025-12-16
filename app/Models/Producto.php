<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'tienda_id',
        'nombre',
        'descripcion',
        'imagen',
        'codigo_barras',
        'precio',
        'precio_mayorista',
        'precio_sugerido',
        'peso',
        'unidad_medida',
        'categoria',
        'temperatura_requerida',
        'lote',
        'fecha_vencimiento',
        'stock',
        'activo',
        'destacado',
        'stock_minimo',
        'estado_aprobacion',
        'propuesto_por_tienda_id',
        'producto_maestro_id',
        'motivo_rechazo'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'precio_mayorista' => 'decimal:2',
        'precio_sugerido' => 'decimal:2',
        'peso' => 'decimal:3',
        'temperatura_requerida' => 'decimal:2',
        'fecha_vencimiento' => 'date',
        'activo' => 'boolean',
        'destacado' => 'boolean',
    ];

    const CATEGORIAS = [
        'tuberculos' => 'Tubérculos',
        'verduras' => 'Verduras y Hortalizas',
        'frutas' => 'Frutas Frescas',
        'legumbres' => 'Legumbres',
        'granos' => 'Granos y Cereales',
        'lacteos' => 'Lácteos del Campo',
        'huevos' => 'Huevos y Aves',
        'miel' => 'Miel y Derivados',
        'hierbas' => 'Hierbas Aromáticas',
        'organicos' => 'Productos Orgánicos',
        'hogar' => 'Hogar y Decoración',
        'otros' => 'Otros'
    ];

    const UNIDADES_MEDIDA = [
        'kg' => 'Kilogramos',
        'g' => 'Gramos',
        'lb' => 'Libras',
        'l' => 'Litros',
        'ml' => 'Mililitros',
        'unidad' => 'Unidades',
        'caja' => 'Cajas',
        'paquete' => 'Paquetes'
    ];

    // === SCOPES ===

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereRaw('stock <= stock_minimo');
    }

    public function scopeDeTienda($query, $tiendaId)
    {
        return $query->where('tienda_id', $tiendaId);
    }

    public function scopeDelAdmin($query)
    {
        return $query->whereNull('tienda_id');
    }

    // Productos de cualquier tienda (no admin) - para ecommerce
    public function scopeDeTiendas($query)
    {
        return $query->whereNotNull('tienda_id');
    }

    // Productos maestros: del admin y aprobados
    public function scopeMaestros($query)
    {
        return $query->whereNull('tienda_id')
                     ->where('estado_aprobacion', 'aprobado');
    }

    // Productos pendientes de aprobación
    public function scopePendientesAprobacion($query)
    {
        return $query->where('estado_aprobacion', 'pendiente');
    }

    // Productos aprobados
    public function scopeAprobados($query)
    {
        return $query->where('estado_aprobacion', 'aprobado');
    }

    // === RELATIONSHIPS ===

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }

    public function propuestoPorTienda()
    {
        return $this->belongsTo(Tienda::class, 'propuesto_por_tienda_id');
    }

    public function productoMaestro()
    {
        return $this->belongsTo(Producto::class, 'producto_maestro_id');
    }

    public function productosDerivados()
    {
        return $this->hasMany(Producto::class, 'producto_maestro_id');
    }

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function solicitudesReposicion()
    {
        return $this->hasMany(SolicitudReposicion::class);
    }

    // === ACCESSORS ===

    public function getCategoriaNombreAttribute()
    {
        return self::CATEGORIAS[$this->categoria] ?? 'No especificado';
    }

    public function getUnidadMedidaNombreAttribute()
    {
        return self::UNIDADES_MEDIDA[$this->unidad_medida] ?? 'No especificado';
    }

    public function getPrecioFormateadoAttribute()
    {
        return number_format($this->precio, 2) . ' Bs';
    }

    public function getPrecioMayoristaFormateadoAttribute()
    {
        return $this->precio_mayorista 
            ? number_format($this->precio_mayorista, 2) . ' Bs' 
            : 'No definido';
    }

    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset('storage/' . $this->imagen);
        }
        return asset('images/producto-default.png');
    }

    public function getNecesitaReposicionAttribute()
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function getEsMaestroAttribute()
    {
        return is_null($this->tienda_id) && $this->estado_aprobacion === 'aprobado';
    }

    public function getEstaPendienteAttribute()
    {
        return $this->estado_aprobacion === 'pendiente';
    }
}