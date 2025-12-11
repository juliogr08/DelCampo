@extends('layouts.app')

@section('title', 'Detalles del Transporte')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="page-title">
            <i class="fas fa-truck me-2"></i>Detalles del Transporte
        </h1>
        <p class="text-muted">Información completa del transporte</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-nature">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Vehículo: {{ $transporte->placa_vehiculo }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Información del Vehículo -->
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-truck me-2"></i>Información del Vehículo
                        </h6>
                        
                        <div class="mb-3">
                            <strong><i class="fas fa-hashtag me-1 text-muted"></i>ID:</strong>
                            <span class="badge badge-nature">#{{ $transporte->id }}</span>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-id-card me-1 text-muted"></i>Placa del Vehículo:</strong>
                            <p class="mb-0">
                                <i class="fas fa-truck text-primary me-1"></i>
                                <strong>{{ $transporte->placa_vehiculo }}</strong>
                            </p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-user me-1 text-muted"></i>Conductor:</strong>
                            <p class="mb-0">
                                <i class="fas fa-user-tie text-info me-1"></i>
                                {{ $transporte->conductor }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-phone me-1 text-muted"></i>Teléfono del Conductor:</strong>
                            <p class="mb-0">
                                <i class="fas fa-phone-alt text-success me-1"></i>
                                {{ $transporte->telefono_conductor }}
                            </p>
                        </div>
                    </div>

                    <!-- Especificaciones Técnicas -->
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-cogs me-2"></i>Especificaciones Técnicas
                        </h6>

                        <div class="mb-3">
                            <strong><i class="fas fa-weight me-1 text-muted"></i>Capacidad de Carga:</strong>
                            <p class="mb-0 fw-bold text-info">{{ $transporte->capacidad_completa }}</p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-thermometer-half me-1 text-muted"></i>Control de Temperatura:</strong>
                            <div class="mt-1">
                                {!! $transporte->tipo_temperatura_badge !!}
                            </div>
                            <small class="text-muted">
                                Rango: {{ $transporte->rango_temperatura }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-circle me-1 text-muted"></i>Estado Actual:</strong>
                            <div class="mt-1">
                                {!! $transporte->estado_badge !!}
                            </div>
                        </div>

                        @if($transporte->rutas_count > 0)
                        <div class="mb-3">
                            <strong><i class="fas fa-route me-1 text-muted"></i>Rutas Asignadas:</strong>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ $transporte->rutas_count }} ruta(s)</span>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Información de Temperatura Detallada -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-temperature-low me-2"></i>Especificaciones de Temperatura
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-thermometer-empty fa-2x text-info mb-2"></i>
                                        <h6 class="card-title">Temperatura Mínima</h6>
                                        <p class="card-text fw-bold text-primary">
                                            {{ $transporte->temperatura_minima ? $transporte->temperatura_minima . '°C' : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-thermometer-full fa-2x text-warning mb-2"></i>
                                        <h6 class="card-title">Temperatura Máxima</h6>
                                        <p class="card-text fw-bold text-primary">
                                            {{ $transporte->temperatura_maxima ? $transporte->temperatura_maxima . '°C' : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-sync-alt fa-2x text-success mb-2"></i>
                                        <h6 class="card-title">Rango Operativo</h6>
                                        <p class="card-text fw-bold text-primary">
                                            {{ $transporte->rango_temperatura }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Auditoría -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="border-top pt-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar-plus me-1"></i>
                                <strong>Creado:</strong> {{ $transporte->created_at->format('d/m/Y H:i') }} | 
                                <i class="fas fa-calendar-check me-1"></i>
                                <strong>Actualizado:</strong> {{ $transporte->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('transportes.index') }}" class="btn btn-nature-warning">
                        <i class="fas fa-arrow-left me-1"></i>Volver a la lista
                    </a>
                    <div>
                        <a href="{{ route('transportes.edit', $transporte->id) }}" class="btn btn-nature-primary">
                            <i class="fas fa-edit me-1"></i>Editar Transporte
                        </a>
                        <button type="button" 
                                class="btn btn-nature-danger" 
                                onclick="confirmDelete{{ $transporte->id }}()">
                            <i class="fas fa-trash me-1"></i>Eliminar Transporte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Eliminación -->
@include('components.delete-modal', [
    'id' => $transporte->id,
    'deleteUrl' => route('transportes.destroy', $transporte->id),
    'message' => '¿Estás seguro de eliminar el transporte con placa \"' . $transporte->placa_vehiculo . '\"?'
])
@endsection