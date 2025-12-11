@extends('tienda.layouts.app')

@section('title', 'Pedido ' . $pedido->codigo)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('tienda.home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tienda.mis-pedidos') }}">Mis Pedidos</a></li>
            <li class="breadcrumb-item active">{{ $pedido->codigo }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pedido #{{ $pedido->codigo }}</h5>
                    {!! $pedido->estado_badge !!}
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Fecha:</strong></p>
                            <p class="text-muted">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Método de Pago:</strong></p>
                            <p class="text-muted">{{ $pedido->metodo_pago_nombre }}</p>
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Productos</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->detalles as $detalle)
                                    <tr>
                                        <td>{{ $detalle->producto->nombre }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>{{ number_format($detalle->precio_unitario, 2) }} Bs</td>
                                        <td>{{ number_format($detalle->subtotal, 2) }} Bs</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Entrega</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Dirección:</strong></p>
                    <p class="text-muted">{{ $pedido->direccion_entrega }}</p>
                    
                    <p class="mb-2"><strong>Almacén de origen:</strong></p>
                    <p class="text-muted">{{ $pedido->almacen->nombre_almacen }}</p>
                    
                    @if($pedido->observaciones)
                        <p class="mb-2"><strong>Observaciones:</strong></p>
                        <p class="text-muted">{{ $pedido->observaciones }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ number_format($pedido->subtotal, 2) }} Bs</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Costo de Envío ({{ $pedido->distancia_km }} km)</span>
                        <span>{{ number_format($pedido->costo_envio, 2) }} Bs</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong class="text-primary fs-5">{{ number_format($pedido->total, 2) }} Bs</strong>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('tienda.mis-pedidos') }}" class="btn btn-outline-primary w-100 mt-3">
                <i class="fas fa-arrow-left me-2"></i>Volver a Mis Pedidos
            </a>
        </div>
    </div>
</div>
@endsection
