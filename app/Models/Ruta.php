<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $table = 'rutas';

    protected $fillable = [
        'transporte_id',
        'origen',
        'origen_lat',
        'origen_lng',
        'destino',
        'destino_lat',
        'destino_lng',
        'fecha_salida',
        'fecha_estimada_llegada',
        'estado_envio',
        'temperatura_registrada',
        'observaciones'
    ];

    protected $casts = [
        'fecha_salida' => 'datetime',
        'fecha_estimada_llegada' => 'datetime',
        'temperatura_registrada' => 'decimal:2',
    ];

    const ESTADOS_ENVIO = [
        'pendiente' => 'Pendiente',
        'en_camino' => 'En Camino',
        'entregado' => 'Entregado',
        'cancelado' => 'Cancelado'
    ];

    const TIPOS_TEMPERATURA = [
        'ambiente' => 'Ambiente (15°C - 25°C)',
        'refrigerado' => 'Refrigerado (2°C - 8°C)',
        'congelado' => 'Congelado (-18°C)'
    ];

    public function getTipoTemperaturaNombreAttribute()
    {
        if (!$this->temperatura_registrada) return 'No registrada';
        
        if ($this->temperatura_registrada >= 15 && $this->temperatura_registrada <= 25) {
            return 'Ambiente (15°C - 25°C)';
        } elseif ($this->temperatura_registrada >= 2 && $this->temperatura_registrada <= 8) {
            return 'Refrigerado (2°C - 8°C)';
        } elseif ($this->temperatura_registrada == -18) {
            return 'Congelado (-18°C)';
        } else {
            return 'Personalizada (' . $this->temperatura_registrada . '°C)';
        }
    }

    public function getEstadoEnvioNombreAttribute()
    {
        return self::ESTADOS_ENVIO[$this->estado_envio] ?? 'Desconocido';
    }

    public function getEstadoEnvioBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'bg-warning text-dark',
            'en_camino' => 'bg-info',
            'entregado' => 'bg-success',
            'cancelado' => 'bg-danger'
        ];
        
        $color = $badges[$this->estado_envio] ?? 'bg-secondary';
        return '<span class="badge ' . $color . '">' . $this->estado_envio_nombre . '</span>';
    }

    public function getDuracionEstimadaAttribute()
    {
        if (!$this->fecha_salida || !$this->fecha_estimada_llegada) {
            return 'N/A';
        }
        
        $diff = $this->fecha_salida->diff($this->fecha_estimada_llegada);
        $hours = $diff->h + ($diff->days * 24);
        
        if ($hours < 1) {
            return $diff->i . ' minutos';
        } elseif ($hours < 24) {
            return $hours . ' horas';
        } else {
            return $diff->days . ' días ' . $diff->h . ' horas';
        }
    }

    public function getTemperaturaFormateadaAttribute()
    {
        return $this->temperatura_registrada ? $this->temperatura_registrada . '°C' : 'No registrada';
    }

    public function getProgresoAttribute()
    {
        $progreso = [
            'pendiente' => 25,
            'en_camino' => 50,
            'entregado' => 100,
            'cancelado' => 0
        ];
        
        return $progreso[$this->estado_envio] ?? 0;
    }

    public function getEstaAtrasadaAttribute()
    {
        if ($this->estado_envio === 'en_camino' && $this->fecha_estimada_llegada->isPast()) {
            return true;
        }
        return false;
    }

    public function transporte()
    {
        return $this->belongsTo(Transporte::class);
    }
}