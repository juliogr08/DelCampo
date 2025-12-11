@extends('layouts.app')

@section('title', 'Detalles de la Ruta')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="page-title">
            <i class="fas fa-route me-2"></i>Detalles de la Ruta
        </h1>
        <p class="text-muted">Información completa de la ruta de transporte</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-nature">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Ruta: {{ $ruta->origen }} → {{ $ruta->destino }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Información de la Ruta -->
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-route me-2"></i>Información de la Ruta
                        </h6>
                        
                        <div class="mb-3">
                            <strong><i class="fas fa-hashtag me-1 text-muted"></i>ID:</strong>
                            <span class="badge badge-nature">#{{ $ruta->id }}</span>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-truck me-1 text-muted"></i>Transporte Asignado:</strong>
                            <div class="mt-1 p-2 rounded" style="background-color: var(--verde-claro);">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-truck text-primary me-2"></i>
                                    <div>
                                        <strong>{{ $ruta->transporte->placa_vehiculo }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Conductor: {{ $ruta->transporte->conductor }}<br>
                                            Capacidad: {{ $ruta->transporte->capacidad_completa }}<br>
                                            Temperatura: {{ $ruta->transporte->tipo_temperatura_nombre }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-map-marker-alt me-1 text-muted"></i>Punto de Origen:</strong>
                            <p class="mb-0">
                                <i class="fas fa-play-circle text-success me-1"></i>
                                <strong>{{ $ruta->origen }}</strong>
                            </p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-flag-checkered me-1 text-muted"></i>Punto de Destino:</strong>
                            <p class="mb-0">
                                <i class="fas fa-stop-circle text-danger me-1"></i>
                                <strong>{{ $ruta->destino }}</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Programación y Estado -->
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>Programación y Estado
                        </h6>

                        <div class="mb-3">
                            <strong><i class="fas fa-play-circle me-1 text-muted"></i>Fecha y Hora de Salida:</strong>
                            <p class="mb-0">
                                <i class="fas fa-clock text-info me-1"></i>
                                {{ $ruta->fecha_salida->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-stop-circle me-1 text-muted"></i>Fecha Estimada de Llegada:</strong>
                            <p class="mb-0">
                                <i class="fas fa-clock text-info me-1"></i>
                                {{ $ruta->fecha_estimada_llegada->format('d/m/Y H:i') }}
                                @if($ruta->esta_atrasada)
                                <span class="badge bg-danger ms-2">Atrasada</span>
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-hourglass-half me-1 text-muted"></i>Duración Estimada:</strong>
                            <p class="mb-0 fw-bold text-info">{{ $ruta->duracion_estimada }}</p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-tasks me-1 text-muted"></i>Estado del Envío:</strong>
                            <div class="mt-1">
                                {!! $ruta->estado_envio_badge !!}
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-thermometer-half me-1 text-muted"></i>Temperatura Registrada:</strong>
                            <p class="mb-0">
                                @if($ruta->temperatura_registrada)
                                    <span class="badge bg-info">{{ $ruta->temperatura_formateada }}</span>
                                @else
                                    <span class="badge bg-secondary">No registrada</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Barra de Progreso -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-chart-line me-2"></i>Progreso del Envío
                        </h6>
                        <div class="progress" style="height: 20px;">
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
                                <strong>{{ $ruta->progreso }}%</strong>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">⏳ Pendiente</small>
                            <small class="text-muted">🚚 En Camino</small>
                            <small class="text-muted">✅ Entregado</small>
                        </div>
                    </div>
                </div>

                <!-- Mapa de Ruta (Simulado) -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-map me-2"></i>Mapa de la Ruta
                        </h6>
                        <div style="height: 200px; background: linear-gradient(45deg, #e8f5e8, #c8e6c9); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 2px solid var(--verde-bosque); position: relative;">
                            <div class="text-center">
                                <div class="mb-2">
                                    <i class="fas fa-map-marker-alt fa-2x text-success"></i>
                                    <i class="fas fa-arrow-right fa-2x text-primary mx-3"></i>
                                    <i class="fas fa-flag-checkered fa-2x text-danger"></i>
                                </div>
                                <h6 class="text-success">{{ $ruta->origen }} → {{ $ruta->destino }}</h6>
                                <p class="text-muted mb-0">Distancia: {{ rand(50, 500) }} km • Tiempo: {{ $ruta->duracion_estimada }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Vista previa del mapa - Integración con Google Maps API
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                @if($ruta->observaciones)
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-success">
                            <i class="fas fa-sticky-note me-2"></i>Observaciones
                        </h6>
                        <div class="p-3 rounded" style="background-color: var(--beige-arena);">
                            {{ $ruta->observaciones }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Información de Auditoría -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="border-top pt-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar-plus me-1"></i>
                                <strong>Creado:</strong> {{ $ruta->created_at->format('d/m/Y H:i') }} | 
                                <i class="fas fa-calendar-check me-1"></i>
                                <strong>Actualizado:</strong> {{ $ruta->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('rutas.index') }}" class="btn btn-nature-warning">
                        <i class="fas fa-arrow-left me-1"></i>Volver a la lista
                    </a>
                    <div>
                        <a href="{{ route('rutas.edit', $ruta->id) }}" class="btn btn-nature-primary">
                            <i class="fas fa-edit me-1"></i>Editar Ruta
                        </a>
                        <button type="button" 
                                class="btn btn-nature-danger" 
                                onclick="confirmDelete{{ $ruta->id }}()">
                            <i class="fas fa-trash me-1"></i>Eliminar Ruta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Eliminación -->
@include('components.delete-modal', [
    'id' => $ruta->id,
    'deleteUrl' => route('rutas.destroy', $ruta->id),
    'message' => '¿Estás seguro de eliminar la ruta de \"' . $ruta->origen . '\" a \"' . $ruta->destino . '\"?'
])
@endsection