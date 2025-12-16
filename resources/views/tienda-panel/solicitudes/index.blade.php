@extends('tienda-panel.layouts.app')

@section('title', 'Solicitudes de Stock - ' . $tienda->nombre)
@section('page-title', 'Solicitudes de Stock')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Solicitudes</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Mis Solicitudes al Administrador</h3>
            <div class="card-tools">
                <a href="{{ route('tienda.panel.solicitudes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nueva Solicitud
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            @if($solicitudes->count() > 0)
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Pago</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudes as $solicitud)
                            <tr>
                                <td>{{ $solicitud->id }}</td>
                                <td>{{ $solicitud->producto->nombre ?? 'N/A' }}</td>
                                <td>{{ $solicitud->cantidad_solicitada }}</td>
                                <td>{{ number_format($solicitud->monto_total ?? 0, 2) }} Bs</td>
                                <td>{!! $solicitud->estado_badge !!}</td>
                                <td>{!! $solicitud->pagado_badge !!}</td>
                                <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($solicitud->estado === 'pendiente')
                                        <form action="{{ route('tienda.panel.solicitudes.destroy', $solicitud) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Cancelar esta solicitud?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Cancelar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-truck-loading fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No has realizado solicitudes de stock</p>
                    <a href="{{ route('tienda.panel.solicitudes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Hacer primera solicitud
                    </a>
                </div>
            @endif
        </div>
        @if($solicitudes->hasPages())
            <div class="card-footer">
                {{ $solicitudes->links() }}
            </div>
        @endif
    </div>
@endsection
