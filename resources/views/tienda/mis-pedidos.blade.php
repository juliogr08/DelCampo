@extends('tienda.layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-box me-2"></i>Mis Pedidos</h2>
    
    @if($pedidos->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                            <tr>
                                <td><strong>{{ $pedido->codigo }}</strong></td>
                                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($pedido->total, 2) }} Bs</td>
                                <td>{!! $pedido->estado_badge !!}</td>
                                <td>
                                    <a href="{{ route('tienda.pedido.detalle', $pedido) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $pedidos->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h4>No tienes pedidos aún</h4>
            <p class="text-muted">¡Haz tu primera compra!</p>
            <a href="{{ route('tienda.catalogo') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>Ver Productos
            </a>
        </div>
    @endif
</div>
@endsection
