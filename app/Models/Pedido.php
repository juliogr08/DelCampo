<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'almacen_id',
        'codigo',
        'estado',
        'subtotal',
        'costo_envio',
        'distancia_km',
        'total',
        'direccion_entrega',
        'entrega_latitud',
        'entrega_longitud',
        'metodo_pago',
        'observaciones'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'costo_envio' => 'decimal:2',
        'distancia_km' => 'decimal:2',
        'total' => 'decimal:2',
        'entrega_latitud' => 'decimal:8',
        'entrega_longitud' => 'decimal:8',
    ];

    const ESTADOS = [
        'pendiente' => 'Pendiente',
        'confirmado' => 'Confirmado',
        'preparando' => 'En Preparación',
        'listo' => 'Listo para Entrega',
        'entregado' => 'Entregado',
        'cancelado' => 'Cancelado'
    ];

    const METODOS_PAGO = [
        'efectivo' => 'Efectivo',
        'qr' => 'Pago QR',
        'transferencia' => 'Transferencia Bancaria'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function getEstadoNombreAttribute()
    {
        return self::ESTADOS[$this->estado] ?? 'Desconocido';
    }

    public function getMetodoPagoNombreAttribute()
    {
        return self::METODOS_PAGO[$this->metodo_pago] ?? 'No especificado';
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'warning',
            'confirmado' => 'info',
            'preparando' => 'primary',
            'listo' => 'success',
            'entregado' => 'success',
            'cancelado' => 'danger'
        ];
        $clase = $badges[$this->estado] ?? 'secondary';
        return "<span class=\"badge bg-{$clase}\">{$this->estado_nombre}</span>";
    }

    public static function generarCodigo()
    {
        $fecha = now()->format('Ymd');
        $ultimo = self::whereDate('created_at', today())->count() + 1;
        return "PED-{$fecha}-" . str_pad($ultimo, 4, '0', STR_PAD_LEFT);
    }
}
