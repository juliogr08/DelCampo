@extends('admin.layouts.app')

@section('title', 'Pedidos - Admin')
@section('page-title', 'Pedidos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pedidos</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-shopping-basket mr-2"></i>Lista de Pedidos</h3>
        </div>
        <div class="card-body">
            <!-- Filtros -->
            <form action="{{ route('admin.pedidos.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="buscar" class="form-control" 
                               placeholder="Buscar código o cliente..." 
                               value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            @foreach(App\Models\Pedido::ESTADOS as $key => $value)
                                <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="fecha_desde" class="form-control" 
                               value="{{ request('fecha_desde') }}" placeholder="Desde">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="fecha_hasta" class="form-control" 
                               value="{{ request('fecha_hasta') }}" placeholder="Hasta">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline-secondary">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                            <tr>
                                <td>
                                    <strong>{{ $pedido->codigo }}</strong>
                                </td>
                                <td>
                                    {{ $pedido->user->name }}
                                    <br>
                                    <small class="text-muted">{{ $pedido->user->telefono ?? $pedido->user->email }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $pedido->detalles_count ?? $pedido->detalles->count() }} items</span>
                                </td>
                                <td>
                                    <strong>{{ number_format($pedido->total, 2) }} Bs</strong>
                                    <br>
                                    <small class="text-muted">Envío: {{ number_format($pedido->costo_envio, 2) }} Bs</small>
                                </td>
                                <td>{!! $pedido->estado_badge !!}</td>
                                <td>
                                    {{ $pedido->created_at->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">{{ $pedido->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.pedidos.show', $pedido) }}" 
                                       class="btn btn-sm btn-primary" title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">No hay pedidos registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pedidos->hasPages())
                <div class="mt-3">
                    {{ $pedidos->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
