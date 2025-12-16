<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tienda extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'slug',
        'logo_path',
        'descripcion',
        'telefono',
        'direccion',
        'latitud',
        'longitud',
        'estado',
        'limite_stock_bajo',
    ];

    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'limite_stock_bajo' => 'integer',
    ];

    const ESTADOS = [
        'pendiente' => 'Pendiente de Aprobación',
        'activa' => 'Activa',
        'suspendida' => 'Suspendida',
    ];

    const LIMITES_STOCK = [5, 10, 20, 30, 50, 100];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($tienda) {
            if (empty($tienda->slug)) {
                $tienda->slug = Str::slug($tienda->nombre);
                $count = static::where('slug', 'like', $tienda->slug . '%')->count();
                if ($count > 0) {
                    $tienda->slug .= '-' . ($count + 1);
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function almacenes()
    {
        return $this->hasMany(Almacen::class);
    }

    public function almacenPrincipal()
    {
        return $this->hasOne(Almacen::class)->where('es_sede_principal', true);
    }

    public function solicitudesReposicion()
    {
        return $this->hasMany(SolicitudReposicion::class, 'tienda_solicitante_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function isActiva()
    {
        return $this->estado === 'activa';
    }

    public function isPendiente()
    {
        return $this->estado === 'pendiente';
    }

    public function getEstadoNombreAttribute()
    {
        return self::ESTADOS[$this->estado] ?? 'Desconocido';
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'warning',
            'activa' => 'success',
            'suspendida' => 'danger',
        ];
        $clase = $badges[$this->estado] ?? 'secondary';
        return "<span class=\"badge bg-{$clase}\">{$this->estado_nombre}</span>";
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return asset('images/tienda-default.png');
    }

    public function getCoordenadasAttribute()
    {
        if ($this->latitud && $this->longitud) {
            return "{$this->latitud}, {$this->longitud}";
        }
        return 'Sin ubicación';
    }

    public function productosConStockBajo()
    {
        return $this->productos()->where('stock', '<=', $this->limite_stock_bajo);
    }
}
