<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;

    protected $table = 'almacenes';

    protected $fillable = [
        'nombre_almacen',
        'ubicacion',
        'latitud',
        'longitud',
        'capacidad',
        'unidad_capacidad',
        'tipo_almacenamiento',
        'temperatura_actual',
        'responsable',
        'telefono_contacto',
        'activo',
        'es_principal'
    ];

    protected $casts = [
        'capacidad' => 'decimal:2',
        'temperatura_actual' => 'decimal:2',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'activo' => 'boolean',
        'es_principal' => 'boolean'
    ];

    const ESTADOS = [
        true => 'Activo',
        false => 'Inactivo'
    ];

    const TIPOS_ALMACENAMIENTO = [
        'ambiente' => 'Ambiente (15°C - 25°C)',
        'refrigerado' => 'Refrigerado (2°C - 8°C)',
        'congelado' => 'Congelado (-18°C)'
    ];

    const UNIDADES_CAPACIDAD = [
        'm2' => 'Metros Cuadrados',
        'hectareas' => 'Hectáreas'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function solicitudesReposicion()
    {
        return $this->hasMany(SolicitudReposicion::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePrincipal($query)
    {
        return $query->where('es_principal', true);
    }

    public function getTipoAlmacenamientoNombreAttribute()
    {
        return self::TIPOS_ALMACENAMIENTO[$this->tipo_almacenamiento] ?? 'No especificado';
    }

    public function getUnidadCapacidadNombreAttribute()
    {
        return self::UNIDADES_CAPACIDAD[$this->unidad_capacidad] ?? 'No especificado';
    }

    public function getEstadoNombreAttribute()
    {
        return self::ESTADOS[$this->activo] ?? 'Desconocido';
    }

    public function getEstadoBadgeAttribute()
    {
        return $this->activo
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-danger">Inactivo</span>';
    }

    public function getCoordenadasAttribute()
    {
        if ($this->latitud && $this->longitud) {
            return "{$this->latitud}, {$this->longitud}";
        }
        return 'Sin ubicación';
    }

    public function calcularDistanciaKm($latitud, $longitud)
    {
        if (!$this->latitud || !$this->longitud) {
            return null;
        }

        $radioTierra = 6371;

        $lat1 = deg2rad($this->latitud);
        $lat2 = deg2rad($latitud);
        $deltaLat = deg2rad($latitud - $this->latitud);
        $deltaLng = deg2rad($longitud - $this->longitud);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($deltaLng / 2) * sin($deltaLng / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($radioTierra * $c, 2);
    }

    public function calcularCostoEnvio($latitud, $longitud, $precioBase = 6, $precioPorKm = 1)
    {
        $distancia = $this->calcularDistanciaKm($latitud, $longitud);
        
        if ($distancia === null) {
            return null;
        }

        return round($precioBase + ($distancia * $precioPorKm), 2);
    }
}