@extends('admin.layouts.app')

@section('title', 'Mis Tiendas - Admin')
@section('page-title', 'Mis Tiendas')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mis Tiendas</li>
@endsection

@section('content')
    <!-- Estadísticas -->
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalTiendas }}</h3>
                    <p>Total Tiendas</p>
                </div>
                <div class="icon"><i class="fas fa-store"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $tiendasActivas }}</h3>
                    <p>Tiendas Activas</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($ventasMes, 2) }} Bs</h3>
                    <p>Ventas a Tiendas (este mes)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
    </div>

    <!-- Lista de Tiendas -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-store mr-2"></i>Tiendas Afiliadas</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Tienda</th>
                        <th>Propietario</th>
                        <th>Contacto</th>
                        <th>Productos Activos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tiendas as $tienda)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($tienda->logo_path)
                                        <img src="{{ asset('storage/' . $tienda->logo_path) }}" 
                                             class="rounded-circle mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-store"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $tienda->nombre }}</strong>
                                        <br><small class="text-muted">{{ $tienda->direccion }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $tienda->user->name ?? 'N/A' }}</td>
                            <td>
                                <i class="fas fa-phone text-muted mr-1"></i>{{ $tienda->telefono }}
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $tienda->productos_count }} productos</span>
                            </td>
                            <td>
                                @if($tienda->estado === 'activa')
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Activa</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($tienda->estado) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.tiendas.show', $tienda) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Ver Historial
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-store fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No hay tiendas registradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tiendas->hasPages())
            <div class="card-footer">
                {{ $tiendas->links() }}
            </div>
        @endif
    </div>
@endsection
