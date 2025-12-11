@extends('layouts.app')

@section('title', 'Gestión de Transportes')

@section('content-header', 'Gestión de Transportes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Transportes</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-truck me-2"></i>Lista de Transportes
        </h3>
        <div class="card-tools">
            <a href="{{ route('transportes.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Nuevo Transporte
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($transportes->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehículo</th>
                        <th>Conductor</th>
                        <th>Capacidad</th>
                        <th>Temperatura</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transportes as $transporte)
                    <tr>
                        <td><strong>#{{ $transporte->id }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-truck text-primary me-2"></i>
                                <div>
                                    <strong>{{ $transporte->placa_vehiculo }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted">{{ $transporte->conductor }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-info">{{ $transporte->capacidad_completa }}</span>
                        </td>
                        <td>
                            {!! $transporte->tipo_temperatura_badge !!}
                            <br>
                            <small class="text-muted">{{ $transporte->rango_temperatura }}</small>
                        </td>
                        <td>
                            <span class="text-muted">{{ $transporte->telefono_conductor }}</span>
                        </td>
                        <td>
                            {!! $transporte->estado_badge !!}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('transportes.show', $transporte->id) }}" 
                                   class="btn btn-info" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('transportes.edit', $transporte->id) }}" 
                                   class="btn btn-warning" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-danger delete-btn" 
                                        title="Eliminar"
                                        data-form="delete-form-{{ $transporte->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $transporte->id }}" 
                                      action="{{ route('transportes.destroy', $transporte->id) }}" 
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
        @else
        <div class="text-center py-5">
            <i class="fas fa-truck fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No hay transportes registrados</h5>
            <p class="text-muted">Comienza agregando tu primer transporte al sistema</p>
            <a href="{{ route('transportes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>Crear Primer Transporte
            </a>
        </div>
        @endif
    </div>
    {{-- ELIMINAMOS EL FOOTER CON PAGINACIÓN --}}
</div>

<!-- Modal de eliminación -->
@include('components.delete-modal')
@endsection