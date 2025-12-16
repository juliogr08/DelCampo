@extends('tienda-panel.layouts.app')

@section('title', 'Mis Propuestas - ' . $tienda->nombre)
@section('page-title', 'Mis Propuestas de Productos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Mis Propuestas</li>
@endsection

@section('content')
    <div class="mb-3">
        <a href="{{ route('tienda.panel.proponer-producto') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Nueva Propuesta
        </a>
        <a href="{{ route('tienda.panel.catalogo-admin') }}" class="btn btn-secondary">
            <i class="fas fa-store mr-1"></i> Ver Catálogo Admin
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Productos Propuestos</h3>
        </div>
        <div class="card-body table-responsive p-0">
            @if($propuestas->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-lightbulb fa-3x text-muted mb-3"></i>
                    <h5>No has propuesto productos</h5>
                    <p class="text-muted">¿No encuentras un producto en el catálogo? Propónelo.</p>
                    <a href="{{ route('tienda.panel.proponer-producto') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Proponer Producto
                    </a>
                </div>
            @else
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($propuestas as $propuesta)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($propuesta->imagen)
                                            <img src="{{ $propuesta->imagen_url }}" 
                                                 class="img-circle mr-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="img-circle bg-light mr-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <strong>{{ $propuesta->nombre }}</strong>
                                    </div>
                                </td>
                                <td>{{ $propuesta->categoria_nombre }}</td>
                                <td>
                                    @if($propuesta->estado_aprobacion === 'pendiente')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock mr-1"></i> Pendiente
                                        </span>
                                    @elseif($propuesta->estado_aprobacion === 'aprobado')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i> Aprobado
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times mr-1"></i> Rechazado
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $propuesta->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($propuesta->estado_aprobacion === 'rechazado' && $propuesta->motivo_rechazo)
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-toggle="modal" 
                                                data-target="#motivoModal{{ $propuesta->id }}">
                                            <i class="fas fa-info-circle"></i> Ver motivo
                                        </button>
                                    @elseif($propuesta->estado_aprobacion === 'aprobado')
                                        <a href="{{ route('tienda.panel.catalogo-admin') }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus"></i> Agregar a tienda
                                        </a>
                                    @else
                                        <span class="text-muted">En revisión...</span>
                                    @endif
                                </td>
                            </tr>

                            @if($propuesta->estado_aprobacion === 'rechazado' && $propuesta->motivo_rechazo)
                            <div class="modal fade" id="motivoModal{{ $propuesta->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Motivo del Rechazo</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Producto:</strong> {{ $propuesta->nombre }}</p>
                                            <hr>
                                            <p>{{ $propuesta->motivo_rechazo }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if($propuestas->hasPages())
            <div class="card-footer">
                {{ $propuestas->links() }}
            </div>
        @endif
    </div>
@endsection
