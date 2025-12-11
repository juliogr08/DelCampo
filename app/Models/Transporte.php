<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
    use HasFactory;

    protected $table = 'transportes';

    protected $fillable = [
        'placa_vehiculo',
        'conductor',
        'capacidad_carga',
        'unidad_carga',
        'tipo_temperatura',
        'temperatura_minima',
        'temperatura_maxima',
        'estado',
        'telefono_conductor'
    ];

    protected $casts = [
        'capacidad_carga' => 'decimal:2',
        'temperatura_minima' => 'decimal:2',
        'temperatura_maxima' => 'decimal:2',
    ];

    const ESTADOS = [
        'disponible' => 'Disponible',
        'en_mantenimiento' => 'En Mantenimiento',
        'en_ruta' => 'En Ruta'
    ];

    const TIPOS_TEMPERATURA = [
        'ambiente' => 'Ambiente',
        'refrigerado' => 'Refrigerado',
        'congelado' => 'Congelado'
    ];

    const UNIDADES_CARGA = [
        'kg' => 'Kilogramos',
        'ton' => 'Toneladas',
        'm3' => 'Metros Cúbicos'
    ];

    public function getEstadoNombreAttribute()
    {
        return self::ESTADOS[$this->estado] ?? 'Desconocido';
    }

    public function getTipoTemperaturaNombreAttribute()
    {
        return self::TIPOS_TEMPERATURA[$this->tipo_temperatura] ?? 'No controlada';
    }

    public function getUnidadCargaNombreAttribute()
    {
        return self::UNIDADES_CARGA[$this->unidad_carga] ?? 'No especificado';
    }

    public function getCapacidadCompletaAttribute()
    {
        if (!$this->capacidad_carga) return 'N/A';
        return number_format($this->capacidad_carga, 2) . ' ' . $this->unidad_carga_nombre;
    }

    public function getRangoTemperaturaAttribute()
    {
        if (!$this->temperatura_minima && !$this->temperatura_maxima) {
            return 'No controlada';
        }
        return $this->temperatura_minima . '°C - ' . $this->temperatura_maxima . '°C';
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'disponible' => 'bg-success',
            'en_mantenimiento' => 'bg-warning text-dark',
            'en_ruta' => 'bg-info'
        ];
        
        $color = $badges[$this->estado] ?? 'bg-secondary';
        return '<span class="badge ' . $color . '">' . $this->estado_nombre . '</span>';
    }

    public function getTipoTemperaturaBadgeAttribute()
    {
        $badges = [
            'ambiente' => 'bg-warning text-dark',
            'refrigerado' => 'bg-info',
            'congelado' => 'bg-primary'
        ];
        
        $color = $badges[$this->tipo_temperatura] ?? 'bg-secondary';
        return '<span class="badge ' . $color . '">' . $this->tipo_temperatura_nombre . '</span>';
    }

    public function rutas()
    {
        return $this->hasMany(Ruta::class);
    }

    public function getRutasCountAttribute()
    {
        return $this->rutas()->count();
    }
}