@extends('tienda-panel.layouts.app')

@section('title', 'Mis Ventas - ' . $tienda->nombre)
@section('page-title', 'Mis Ventas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Ventas</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pedidos con mis productos</h3>
        </div>
        <div class="card-body table-responsive p-0">
            @if($pedidos->count() > 0)
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Total Pedido</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                            <tr>
                                <td>{{ $pedido->id }}</td>
                                <td>{{ $pedido->user->name ?? 'Cliente' }}</td>
                                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td>{!! $pedido->estado_badge ?? '<span class="badge badge-secondary">'.ucfirst($pedido->estado).'</span>' !!}</td>
                                <td>{{ number_format($pedido->total, 2) }} Bs</td>
                                <td>
                                    @if($pedido->estado === 'pendiente')
                                        <form action="{{ route('tienda.panel.ventas.confirmar', $pedido) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Confirmar pedido y descontar stock?')">
                                                <i class="fas fa-check"></i> Confirmar
                                            </button>
                                        </form>
                                    @elseif(in_array($pedido->estado, ['confirmado', 'preparando', 'listo']))
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                                <i class="fas fa-exchange-alt"></i> Estado
                                            </button>
                                            <div class="dropdown-menu">
                                                @if($pedido->estado === 'confirmado')
                                                    <form action="{{ route('tienda.panel.ventas.estado', $pedido) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="estado" value="preparando">
                                                        <button type="submit" class="dropdown-item"><i class="fas fa-box text-info"></i> En Preparación</button>
                                                    </form>
                                                @endif
                                                @if(in_array($pedido->estado, ['confirmado', 'preparando']))
                                                    <form action="{{ route('tienda.panel.ventas.estado', $pedido) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="estado" value="listo">
                                                        <button type="submit" class="dropdown-item"><i class="fas fa-check-circle text-success"></i> Listo para Entrega</button>
                                                    </form>
                                                @endif
                                                @if(in_array($pedido->estado, ['confirmado', 'preparando', 'listo']))
                                                    <form action="{{ route('tienda.panel.ventas.estado', $pedido) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="estado" value="entregado">
                                                        <button type="submit" class="dropdown-item"><i class="fas fa-truck text-primary"></i> Entregado</button>
                                                    </form>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('tienda.panel.ventas.estado', $pedido) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="estado" value="cancelado">
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Cancelar pedido? El stock será devuelto.')"><i class="fas fa-times"></i> Cancelar</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <a href="{{ route('tienda.panel.ventas.show', $pedido) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Aún no tienes ventas</p>
                    <p class="text-muted">Cuando alguien compre tus productos, aparecerán aquí</p>
                </div>
            @endif
        </div>
        @if($pedidos->hasPages())
            <div class="card-footer">
                {{ $pedidos->links() }}
            </div>
        @endif
    </div>
@endsection
