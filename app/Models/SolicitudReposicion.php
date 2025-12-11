<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudReposicion extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_reposicion';

    protected $fillable = [
        'almacen_id',
        'producto_id',
        'cantidad_solicitada',
        'cantidad_recibida',
        'estado',
        'almacen_externo_id',
        'notas',
        'fecha_respuesta'
    ];

    protected $casts = [
        'fecha_respuesta' => 'datetime',
    ];

    const ESTADOS = [
        'pendiente' => 'Pendiente',
        'aceptada' => 'Aceptada',
        'rechazada' => 'Rechazada',
        'en_transito' => 'En Tránsito',
        'recibida' => 'Recibida'
    ];

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function getEstadoNombreAttribute()
    {
        return self::ESTADOS[$this->estado] ?? 'Desconocido';
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'warning',
            'aceptada' => 'info',
            'rechazada' => 'danger',
            'en_transito' => 'primary',
            'recibida' => 'success'
        ];
        $clase = $badges[$this->estado] ?? 'secondary';
        return "<span class=\"badge bg-{$clase}\">{$this->estado_nombre}</span>";
    }
}
