@extends('layouts.app')

@section('title', 'Gestión de Almacenes')

@section('content-header', 'Gestión de Almacenes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Almacenes</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-warehouse me-2"></i>Lista de Almacenes
        </h3>
        <div class="card-tools">
            <a href="{{ route('almacenes.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Nuevo Almacén
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($almacenes->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Almacén</th>
                        <th>Ubicación</th>
                        <th>Capacidad</th>
                        <th>Temperatura</th>
                        <th>Responsable</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($almacenes as $almacen)
                    <tr>
                        <td><strong>#{{ $almacen->id }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-warehouse text-primary me-2"></i>
                                <div>
                                    <strong>{{ $almacen->nombre_almacen }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted">{{ $almacen->ubicacion }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-info">{{ $almacen->capacidad_formateada }}</span>
                        </td>
                        <td>
                            @if($almacen->temperatura_actual)
                                <span class="badge bg-info">{{ $almacen->temperatura_formateada }}</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">{{ $almacen->responsable }}</span>
                        </td>
                        <td>
                            {!! $almacen->estado_badge !!}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('almacenes.show', $almacen->id) }}" 
                                   class="btn btn-info" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('almacenes.edit', $almacen->id) }}" 
                                   class="btn btn-warning" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-danger delete-btn" 
                                        title="Eliminar"
                                        data-form="delete-form-{{ $almacen->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $almacen->id }}" 
                                      action="{{ route('almacenes.destroy', $almacen->id) }}" 
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- CONTADOR DE REGISTROS --}}
        <div class="mt-3 text-muted">
            <small>Total de registros: {{ $almacenes->count() }}</small>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-warehouse fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No hay almacenes registrados</h5>
            <p class="text-muted">Comienza agregando tu primer almacén al sistema</p>
            <a href="{{ route('almacenes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>Crear Primer Almacén
            </a>
        </div>
        @endif
    </div>
    {{-- ELIMINAMOS EL FOOTER CON PAGINACIÓN --}}
</div>

<!-- Modal de eliminación -->
@include('components.delete-modal')
@endsection