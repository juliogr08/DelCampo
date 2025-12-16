<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreCaja extends Model
{
    use HasFactory;

    protected $table = 'cierres_caja';

    protected $fillable = [
        'tienda_id',
        'fecha',
        'monto_apertura',
        'total_ventas',
        'total_efectivo',
        'total_tarjeta',
        'total_qr',
        'total_transferencia',
        'monto_contado',
        'diferencia',
        'observaciones',
        'estado',
        'abierta_por',
        'cerrada_por',
        'hora_apertura',
        'hora_cierre',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto_apertura' => 'decimal:2',
        'total_ventas' => 'decimal:2',
        'total_efectivo' => 'decimal:2',
        'total_tarjeta' => 'decimal:2',
        'total_qr' => 'decimal:2',
        'total_transferencia' => 'decimal:2',
        'monto_contado' => 'decimal:2',
        'diferencia' => 'decimal:2',
        'hora_apertura' => 'datetime',
        'hora_cierre' => 'datetime',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }

    public function usuarioApertura()
    {
        return $this->belongsTo(User::class, 'abierta_por');
    }

    public function usuarioCierre()
    {
        return $this->belongsTo(User::class, 'cerrada_por');
    }

    public function getEstaAbiertaAttribute()
    {
        return $this->estado === 'abierta';
    }

    public function getEstaCerradaAttribute()
    {
        return $this->estado === 'cerrada';
    }

    public function getEstadoBadgeAttribute()
    {
        if ($this->estado === 'abierta') {
            return '<span class="badge badge-success"><i class="fas fa-unlock mr-1"></i>Abierta</span>';
        }
        return '<span class="badge badge-secondary"><i class="fas fa-lock mr-1"></i>Cerrada</span>';
    }

    public function getDiferenciaBadgeAttribute()
    {
        if (is_null($this->diferencia)) {
            return '<span class="badge badge-secondary">-</span>';
        }
        
        if ($this->diferencia == 0) {
            return '<span class="badge badge-success">Cuadra</span>';
        } elseif ($this->diferencia > 0) {
            return '<span class="badge badge-info">Sobrante: ' . number_format($this->diferencia, 2) . ' Bs</span>';
        } else {
            return '<span class="badge badge-danger">Faltante: ' . number_format(abs($this->diferencia), 2) . ' Bs</span>';
        }
    }
}
