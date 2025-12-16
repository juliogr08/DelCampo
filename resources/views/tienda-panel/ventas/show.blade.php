@extends('tienda-panel.layouts.app')

@section('title', 'Detalle Venta #' . $pedido->id . ' - ' . $tienda->nombre)
@section('page-title', 'Detalle de Venta #' . $pedido->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.ventas.index') }}">Ventas</a></li>
    <li class="breadcrumb-item active">Pedido #{{ $pedido->id }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Productos de mi tienda en este pedido</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotal = 0; @endphp
                            @foreach($detallesTienda as $detalle)
                                @php $subtotal += $detalle->cantidad * $detalle->precio_unitario; @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $detalle->producto->imagen_url }}" 
                                                 class="img-thumbnail mr-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            {{ $detalle->producto->nombre }}
                                        </div>
                                    </td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>{{ number_format($detalle->precio_unitario, 2) }} Bs</td>
                                    <td>{{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }} Bs</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td colspan="3" class="text-right">Subtotal de mi tienda:</td>
                                <td>{{ number_format($subtotal, 2) }} Bs</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información del Pedido</h3>
                </div>
                <div class="card-body">
                    <p><strong>Cliente:</strong> {{ $pedido->user->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $pedido->user->email ?? 'N/A' }}</p>
                    <p><strong>Teléfono:</strong> {{ $pedido->user->telefono ?? 'N/A' }}</p>
                    <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Estado:</strong> {!! $pedido->estado_badge ?? '<span class="badge badge-secondary">'.ucfirst($pedido->estado).'</span>' !!}</p>
                    <hr>
                    <p><strong>Total del Pedido:</strong> {{ number_format($pedido->total, 2) }} Bs</p>
                </div>
            </div>
            <a href="{{ route('tienda.panel.ventas.index') }}" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left mr-1"></i> Volver a Ventas
            </a>
        </div>
    </div>
@endsection
