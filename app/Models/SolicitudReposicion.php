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
        'tienda_solicitante_id',
        'producto_id',
        'cantidad_solicitada',
        'cantidad_recibida',
        'monto_total',
        'pagado',
        'estado',
        'tipo',
        'almacen_externo_id',
        'notas',
        'fecha_respuesta'
    ];

    protected $casts = [
        'fecha_respuesta' => 'datetime',
        'monto_total' => 'decimal:2',
        'pagado' => 'boolean',
    ];

    const ESTADOS = [
        'pendiente' => 'Pendiente',
        'aceptada' => 'Aceptada',
        'rechazada' => 'Rechazada',
        'en_transito' => 'En Tránsito',
        'recibida' => 'Recibida'
    ];

    const TIPOS = [
        'tienda_a_admin' => 'Tienda a Admin',
        'admin_a_productor' => 'Admin a Productor',
    ];

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function tiendaSolicitante()
    {
        return $this->belongsTo(Tienda::class, 'tienda_solicitante_id');
    }

    public function scopeDeTiendas($query)
    {
        return $query->where('tipo', 'tienda_a_admin');
    }

    public function scopeDeProductores($query)
    {
        return $query->where('tipo', 'admin_a_productor');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function getEstadoNombreAttribute()
    {
        return self::ESTADOS[$this->estado] ?? 'Desconocido';
    }

    public function getTipoNombreAttribute()
    {
        return self::TIPOS[$this->tipo] ?? 'Desconocido';
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

    public function getPagadoBadgeAttribute()
    {
        return $this->pagado
            ? '<span class="badge bg-success">Pagado</span>'
            : '<span class="badge bg-warning">Pendiente</span>';
    }
}

