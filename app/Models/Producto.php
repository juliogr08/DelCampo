<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'codigo_barras',
        'precio',
        'peso',
        'unidad_medida',
        'categoria',
        'temperatura_requerida',
        'lote',
        'fecha_vencimiento',
        'stock',
        'activo',
        'destacado',
        'stock_minimo'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
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

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function solicitudesReposicion()
    {
        return $this->hasMany(SolicitudReposicion::class);
    }

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
}