@extends('layouts.app')

@section('title', 'Gestión de Rutas')

@section('content-header', 'Gestión de Rutas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Rutas</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-route me-2"></i>Lista de Rutas
        </h3>
        <div class="card-tools">
            <a href="{{ route('rutas.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Nueva Ruta
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($rutas->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Transporte</th>
                        <th>Ruta</th>
                        <th>Fechas</th>
                        <th>Temperatura</th>
                        <th>Progreso</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rutas as $ruta)
                    <tr class="{{ $ruta->esta_atrasada ? 'table-warning' : '' }}">
                        <td><strong>#{{ $ruta->id }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-truck text-primary me-2"></i>
                                <div>
                                    <strong>{{ $ruta->transporte->placa_vehiculo }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $ruta->transporte->conductor }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>📍 {{ $ruta->origen }}</strong>
                                <br>
                                <strong>🎯 {{ $ruta->destino }}</strong>
                            </div>
                        </td>
                        <td>
                            <small>
                                <strong>Salida:</strong> {{ $ruta->fecha_salida->format('d/m H:i') }}<br>
                                <strong>Llegada:</strong> {{ $ruta->fecha_estimada_llegada->format('d/m H:i') }}
                                @if($ruta->esta_atrasada)
                                <br><span class="badge bg-danger">Atrasada</span>
                                @endif
                            </small>
                        </td>
                        <td>
                            @if($ruta->temperatura_registrada)
                                <span class="badge bg-info">{{ $ruta->temperatura_formateada }}</span>
                            @else
                                <span class="badge bg-secondary">No registrada</span>
                            @endif
                        </td>
                        <td>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar 
                                    @if($ruta->estado_envio == 'entregado') bg-success
                                    @elseif($ruta->estado_envio == 'en_camino') bg-info
                                    @elseif($ruta->estado_envio == 'pendiente') bg-warning
                                    @else bg-danger @endif" 
                                    role="progressbar" 
                                    style="width: {{ $ruta->progreso }}%"
                                    aria-valuenow="{{ $ruta->progreso }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">{{ $ruta->progreso }}%</small>
                        </td>
                        <td>
                            {!! $ruta->estado_envio_badge !!}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('rutas.show', $ruta->id) }}" 
                                   class="btn btn-info" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('rutas.edit', $ruta->id) }}" 
                                   class="btn btn-warning" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-danger delete-btn" 
                                        title="Eliminar"
                                        data-form="delete-form-{{ $ruta->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $ruta->id }}" 
                                      action="{{ route('rutas.destroy', $ruta->id) }}" 
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
            <i class="fas fa-route fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No hay rutas registradas</h5>
            <p class="text-muted">Comienza planificando tu primera ruta de transporte</p>
            <a href="{{ route('rutas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>Crear Primera Ruta
            </a>
        </div>
        @endif
    </div>
    {{-- ELIMINAMOS EL FOOTER CON PAGINACIÓN --}}
</div>

<!-- Modal de eliminación -->
@include('components.delete-modal')
@endsection