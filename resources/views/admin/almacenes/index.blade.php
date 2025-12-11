@extends('admin.layouts.app')

@section('title', 'Almacenes - Admin')
@section('page-title', 'Almacenes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Almacenes</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-warehouse mr-2"></i>Lista de Almacenes</h3>
            <a href="{{ route('admin.almacenes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Nuevo Almacén
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Coordenadas</th>
                            <th>Responsable</th>
                            <th>Estado</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($almacenes as $almacen)
                            <tr>
                                <td>
                                    <strong>{{ $almacen->nombre_almacen }}</strong>
                                    @if($almacen->es_principal)
                                        <span class="badge badge-success ml-1">Principal</span>
                                    @endif
                                </td>
                                <td>{{ strlen($almacen->ubicacion) > 40 ? substr($almacen->ubicacion, 0, 40) . '...' : $almacen->ubicacion }}</td>
                                <td>
                                    @if($almacen->latitud && $almacen->longitud)
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt text-danger"></i>
                                            {{ number_format($almacen->latitud, 4) }}, {{ number_format($almacen->longitud, 4) }}
                                        </small>
                                    @else
                                        <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Sin ubicación</span>
                                    @endif
                                </td>
                                <td>{{ $almacen->responsable ?? '-' }}</td>
                                <td>{!! $almacen->estado_badge !!}</td>
                                <td>
                                    <a href="{{ route('admin.almacenes.edit', $almacen) }}" 
                                       class="btn btn-sm btn-info" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.almacenes.destroy', $almacen) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este almacén?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-warehouse fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">No hay almacenes registrados</p>
                                    <a href="{{ route('admin.almacenes.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus"></i> Crear primer almacén
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($almacenes->hasPages())
                <div class="mt-3">
                    {{ $almacenes->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
