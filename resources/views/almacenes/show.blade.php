@extends('layouts.app')

@section('title', 'Detalles del Almacén')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="page-title">
            <i class="fas fa-warehouse me-2"></i>Detalles del Almacén
        </h1>
        <p class="text-muted">Información completa del almacén</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-nature">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>{{ $almacen->nombre_almacen }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Información Básica -->
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-info-circle me-2"></i>Información Básica
                        </h6>
                        
                        <div class="mb-3">
                            <strong><i class="fas fa-hashtag me-1 text-muted"></i>ID:</strong>
                            <span class="badge badge-nature">#{{ $almacen->id }}</span>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-warehouse me-1 text-muted"></i>Nombre:</strong>
                            <p class="mb-0">{{ $almacen->nombre_almacen }}</p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-map-marker-alt me-1 text-muted"></i>Ubicación:</strong>
                            <p class="mb-0">
                                <i class="fas fa-map-pin text-danger me-1"></i>
                                {{ $almacen->ubicacion }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-user me-1 text-muted"></i>Responsable:</strong>
                            <p class="mb-0">{{ $almacen->responsable }}</p>
                        </div>
                    </div>

                    <!-- Especificaciones -->
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-cogs me-2"></i>Especificaciones
                        </h6>

                        <div class="mb-3">
                            <strong><i class="fas fa-arrows-alt me-1 text-muted"></i>Capacidad:</strong>
                            <p class="mb-0 fw-bold text-info">{{ $almacen->capacidad_completa }}</p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-thermometer-half me-1 text-muted"></i>Tipo de Almacenamiento:</strong>
                            @if($almacen->tipo_almacenamiento == 'ambiente')
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-sun me-1"></i>Ambiente
                                </span>
                            @elseif($almacen->tipo_almacenamiento == 'refrigerado')
                                <span class="badge bg-info">
                                    <i class="fas fa-snowflake me-1"></i>Refrigerado
                                </span>
                            @else
                                <span class="badge bg-primary">
                                    <i class="fas fa-icicles me-1"></i>Congelado
                                </span>
                            @endif
                            <br>
                            <small class="text-muted">
                                @if($almacen->tipo_almacenamiento == 'ambiente')
                                    15°C - 25°C
                                @elseif($almacen->tipo_almacenamiento == 'refrigerado')
                                    2°C - 8°C
                                @else
                                    -18°C
                                @endif
                            </small>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-phone me-1 text-muted"></i>Teléfono Contacto:</strong>
                            <p class="mb-0">
                                <i class="fas fa-phone-alt text-success me-1"></i>
                                {{ $almacen->telefono_contacto }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-circle me-1 text-muted"></i>Estado:</strong>
                            {!! $almacen->estado_badge !!}
                        </div>
                    </div>
                </div>

                <!-- Mapa de Ubicación (Simulado) -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-map me-2"></i>Ubicación en Mapa
                        </h6>
                        <div style="height: 200px; background: linear-gradient(45deg, #e8f5e8, #c8e6c9); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 2px solid var(--verde-bosque);">
                            <div class="text-center">
                                <i class="fas fa-map-marker-alt fa-2x text-danger mb-2"></i>
                                <h6 class="text-success">{{ $almacen->nombre_almacen }}</h6>
                                <p class="text-muted mb-0">{{ $almacen->ubicacion }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Vista previa del mapa - Integración con Google Maps
                                </small>
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
                                <strong>Creado:</strong> {{ $almacen->created_at ? $almacen->created_at->format('d/m/Y H:i') : 'N/A' }} | 
                                <i class="fas fa-calendar-check me-1"></i>
                                <strong>Actualizado:</strong> {{ $almacen->updated_at ? $almacen->updated_at->format('d/m/Y H:i') : 'N/A' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('almacenes.index') }}" class="btn btn-nature-warning">
                        <i class="fas fa-arrow-left me-1"></i>Volver a la lista
                    </a>
                    <div>
                        <a href="{{ route('almacenes.edit', $almacen->id) }}" class="btn btn-nature-primary">
                            <i class="fas fa-edit me-1"></i>Editar Almacén
                        </a>
                        <button type="button" 
                                class="btn btn-nature-danger" 
                                onclick="confirmDelete{{ $almacen->id }}()">
                            <i class="fas fa-trash me-1"></i>Eliminar Almacén
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Eliminación -->
@include('components.delete-modal', [
    'id' => $almacen->id,
    'deleteUrl' => route('almacenes.destroy', $almacen->id),
    'message' => '¿Estás seguro de eliminar el almacén \"' . $almacen->nombre_almacen . '\"?'
])
@endsection