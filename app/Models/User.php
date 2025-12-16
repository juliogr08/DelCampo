<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'telefono',
        'direccion',
        'ciudad',
        'latitud',
        'longitud',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];

    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->rol === 'admin';
    }

    public function isCliente()
    {
        return $this->hasRole('cliente') || $this->rol === 'cliente';
    }

    public function isTienda()
    {
        return $this->hasRole('tienda') || $this->rol === 'tienda';
    }

    public function tienda()
    {
        return $this->hasOne(Tienda::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function getRolNombreAttribute()
    {
        if ($this->roles->isNotEmpty()) {
            $roleName = $this->roles->first()->name;
            $nombres = [
                'admin' => 'Administrador',
                'cliente' => 'Cliente',
                'tienda' => 'Tienda',
            ];
            return $nombres[$roleName] ?? ucfirst($roleName);
        }
        $nombres = [
            'admin' => 'Administrador',
            'cliente' => 'Cliente',
            'tienda' => 'Tienda',
        ];
        return $nombres[$this->rol] ?? 'Cliente';
    }

    public function getDireccionCompletaAttribute()
    {
        $partes = array_filter([$this->direccion, $this->ciudad]);
        return implode(', ', $partes) ?: 'Sin dirección';
    }
}

