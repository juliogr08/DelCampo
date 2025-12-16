@extends('admin.layouts.app')

@section('title', $tienda->nombre . ' - Admin')
@section('page-title', 'Tienda: ' . $tienda->nombre)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.tiendas.index') }}">Mis Tiendas</a></li>
    <li class="breadcrumb-item active">{{ $tienda->nombre }}</li>
@endsection

@section('content')
    <!-- Información de la Tienda -->
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($tienda->logo_path)
                            <img class="profile-user-img img-fluid img-circle" 
                                 src="{{ asset('storage/' . $tienda->logo_path) }}" 
                                 alt="{{ $tienda->nombre }}">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-store fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="profile-username text-center mt-3">{{ $tienda->nombre }}</h3>
                    <p class="text-muted text-center">{{ $tienda->user->name ?? 'N/A' }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b><i class="fas fa-map-marker-alt text-danger mr-2"></i>Dirección</b>
                            <span class="float-right text-muted">{{ Str::limit($tienda->direccion, 25) }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-phone text-success mr-2"></i>Teléfono</b>
                            <span class="float-right">{{ $tienda->telefono }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-envelope text-info mr-2"></i>Email</b>
                            <span class="float-right">{{ $tienda->user->email ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-circle mr-2 {{ $tienda->estado === 'activa' ? 'text-success' : 'text-secondary' }}"></i>Estado</b>
                            <span class="float-right">
                                @if($tienda->estado === 'activa')
                                    <span class="badge badge-success">Activa</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($tienda->estado) }}</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Estadísticas de la tienda -->
            <div class="row">
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pedidos</span>
                            <span class="info-box-number">{{ $stats['total_pedidos'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pedidos Recibidos</span>
                            <span class="info-box-number">{{ $stats['total_recibidos'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-money-bill"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Vendido</span>
                            <span class="info-box-number">{{ number_format($stats['total_vendido'], 2) }} Bs</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-cubes"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Productos Activos</span>
                            <span class="info-box-number">{{ $stats['productos_activos'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($stats['pendiente_pago'] > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Pendiente de pago: <strong>{{ number_format($stats['pendiente_pago'], 2) }} Bs</strong>
            </div>
            @endif
        </div>
    </div>

    <!-- Historial de Solicitudes -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-history mr-2"></i>Historial de Pedidos</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Pagado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $solicitud)
                        <tr>
                            <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $solicitud->producto->nombre ?? 'N/A' }}</td>
                            <td>
                                {{ $solicitud->cantidad_solicitada }}
                                @if($solicitud->cantidad_recibida)
                                    <small class="text-success">(recibió: {{ $solicitud->cantidad_recibida }})</small>
                                @endif
                            </td>
                            <td>{{ number_format($solicitud->monto_total ?? 0, 2) }} Bs</td>
                            <td>{!! $solicitud->estado_badge !!}</td>
                            <td>{!! $solicitud->pagado_badge !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Esta tienda no ha realizado pedidos</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($solicitudes->hasPages())
            <div class="card-footer">
                {{ $solicitudes->links() }}
            </div>
        @endif
    </div>

    <a href="{{ route('admin.tiendas.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Mis Tiendas
    </a>
@endsection
