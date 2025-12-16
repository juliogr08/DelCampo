@extends('layouts.app')

@section('title', 'Editar Ruta - Del Campo')

@section('content-header', 'Editar Ruta')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('rutas.index') }}">Rutas</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline shadow-lg modern-card">
                    <div class="card-header bg-gradient-primary">
                        <h3 class="card-title text-white">
                            <i class="fas fa-edit mr-2"></i>Editar Ruta: {{ $ruta->origen }} → {{ $ruta->destino }}
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
            <div class="card-body p-4">
                <form action="{{ route('rutas.update', $ruta->id) }}" method="POST" id="rutaForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row justify-content-center">
                        <!-- Información de la Ruta -->
                        <div class="col-12 col-lg-10 mb-4">
                            <div class="card shadow-sm border-0 modern-inner-card" style="border-left: 5px solid #28a745 !important;">
                                <div class="card-header bg-gradient-success text-white">
                                    <h4 class="card-title mb-0">
                                        <i class="fas fa-route mr-2"></i>Información de la Ruta
                                    </h4>
                                </div>
                                <div class="card-body p-4">
                                    <div class="form-group">
                                        <label for="transporte_id" class="font-weight-bold">
                                            <i class="fas fa-truck text-primary mr-2"></i>Transporte Asignado 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-truck"></i>
                                                </span>
                                            </div>
                                            <select class="form-control" id="transporte_id" name="transporte_id" required>
                                                <option value="">Seleccionar transporte</option>
                                                @foreach($transportes as $transporte)
                                                    <option value="{{ $transporte->id }}" 
                                                            data-capacidad="{{ $transporte->capacidad_completa }}"
                                                            data-temperatura="{{ $transporte->tipo_temperatura_nombre }}"
                                                            {{ $transporte->id == $ruta->transporte_id ? 'selected' : '' }}>
                                                        {{ $transporte->placa_vehiculo }} - {{ $transporte->conductor }} 
                                                        ({{ $transporte->capacidad_completa }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <small class="form-text text-muted mt-2" id="info-transporte">
                                            @if($ruta->transporte)
                                            <i class="fas fa-info-circle mr-1"></i><strong>Especificaciones actuales:</strong><br>
                                            • Capacidad: {{ $ruta->transporte->capacidad_completa }}<br>
                                            • Control de temperatura: {{ $ruta->transporte->tipo_temperatura_nombre }}
                                            @else
                                            <i class="fas fa-info-circle mr-1"></i>Selecciona un transporte para ver sus especificaciones
                                            @endif
                                        </small>
                                        <div class="invalid-feedback">Por favor selecciona un transporte.</div>
                                    </div>

                                    <!-- Punto de Origen con Mapa -->
                                    <div class="form-group">
                                        <label for="origen" class="font-weight-bold">
                                            <i class="fas fa-map-marker-alt text-info mr-2"></i>Punto de Origen 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="mb-2">
                                            <select class="form-control" id="selectOrigenAlmacen">
                                                <option value="">-- Seleccionar desde almacén existente --</option>
                                                @foreach($almacenes as $almacen)
                                                    <option value="{{ $almacen->ubicacion }}" 
                                                            data-nombre="{{ $almacen->nombre_almacen }}"
                                                            {{ $almacen->ubicacion == $ruta->origen ? 'selected' : '' }}>
                                                        {{ $almacen->nombre_almacen }} - {{ $almacen->ubicacion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-info text-white">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="origen" name="origen" 
                                                   value="{{ $ruta->origen }}" required 
                                                   placeholder="Selecciona desde almacén o en el mapa"
                                                   readonly>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-info" id="btnSeleccionarOrigen">
                                                    <i class="fas fa-map mr-1"></i>Cambiar
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" id="origen_lat" name="origen_lat" value="{{ $ruta->origen_lat }}">
                                        <input type="hidden" id="origen_lng" name="origen_lng" value="{{ $ruta->origen_lng }}">
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Selecciona desde un almacén existente o haz clic en el botón para seleccionar en el mapa
                                        </small>
                                        <div class="invalid-feedback">Por favor selecciona un punto de origen.</div>
                                    </div>

                                    <!-- Punto de Destino con Mapa -->
                                    <div class="form-group">
                                        <label for="destino" class="font-weight-bold">
                                            <i class="fas fa-flag-checkered text-success mr-2"></i>Punto de Destino 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="mb-2">
                                            <select class="form-control" id="selectDestinoAlmacen">
                                                <option value="">-- Seleccionar desde almacén existente --</option>
                                                @foreach($almacenes as $almacen)
                                                    <option value="{{ $almacen->ubicacion }}" 
                                                            data-nombre="{{ $almacen->nombre_almacen }}"
                                                            {{ $almacen->ubicacion == $ruta->destino ? 'selected' : '' }}>
                                                        {{ $almacen->nombre_almacen }} - {{ $almacen->ubicacion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-success text-white">
                                                    <i class="fas fa-flag-checkered"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="destino" name="destino" 
                                                   value="{{ $ruta->destino }}" required 
                                                   placeholder="Selecciona desde almacén o en el mapa"
                                                   readonly>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-success" id="btnSeleccionarDestino">
                                                    <i class="fas fa-map mr-1"></i>Cambiar
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" id="destino_lat" name="destino_lat" value="{{ $ruta->destino_lat }}">
                                        <input type="hidden" id="destino_lng" name="destino_lng" value="{{ $ruta->destino_lng }}">
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Selecciona desde un almacén existente o haz clic en el botón para seleccionar en el mapa
                                        </small>
                                        <div class="invalid-feedback">Por favor selecciona un punto de destino.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Programación -->
                        <div class="col-12 col-lg-10 mb-4">
                            <div class="card shadow-sm border-0 modern-inner-card" style="border-left: 5px solid #17a2b8 !important;">
                                <div class="card-header bg-gradient-info text-white">
                                    <h4 class="card-title mb-0">
                                        <i class="fas fa-calendar-alt mr-2"></i>Programación
                                    </h4>
                                </div>
                                <div class="card-body p-4">
                                    <div class="form-group">
                                        <label for="fecha_salida" class="font-weight-bold">
                                            <i class="fas fa-play-circle text-primary mr-2"></i>Fecha y Hora de Salida 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-play-circle"></i>
                                                </span>
                                            </div>
                                            <input type="datetime-local" class="form-control" id="fecha_salida" name="fecha_salida" 
                                                   value="{{ $ruta->fecha_salida->format('Y-m-d\TH:i') }}" required>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>La fecha de salida no puede ser en el pasado
                                        </small>
                                        <div class="invalid-feedback">Por favor ingresa una fecha y hora de salida válida.</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="fecha_estimada_llegada" class="font-weight-bold">
                                            <i class="fas fa-stop-circle text-success mr-2"></i>Fecha y Hora Estimada de Llegada 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-success text-white">
                                                    <i class="fas fa-stop-circle"></i>
                                                </span>
                                            </div>
                                            <input type="datetime-local" class="form-control" id="fecha_estimada_llegada" name="fecha_estimada_llegada" 
                                                   value="{{ $ruta->fecha_estimada_llegada->format('Y-m-d\TH:i') }}" required>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Debe ser posterior a la fecha de salida
                                        </small>
                                        <div class="invalid-feedback">Por favor ingresa una fecha y hora de llegada válida.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Temperatura y Observaciones -->
                    <div class="row justify-content-center mt-3">
                        <div class="col-12 col-lg-10 mb-4">
                            <div class="card shadow-sm border-0 modern-inner-card" style="border-left: 5px solid #ffc107 !important;">
                                <div class="card-header bg-gradient-warning text-dark">
                                    <h4 class="card-title mb-0">
                                        <i class="fas fa-thermometer-half mr-2"></i>Temperatura y Observaciones
                                    </h4>
                                </div>
                                <div class="card-body p-4">
                                    <!-- Selector de Temperatura -->
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-thermometer-half text-warning mr-2"></i>Temperatura Registrada
                                        </label>
                                        <div class="temperature-selector">
                                            <div class="row">
                                                @php
                                                    // Determinar temperatura actual basada en el valor numérico
                                                    $temperaturaActual = 'ambiente';
                                                    if ($ruta->temperatura_registrada >= 2 && $ruta->temperatura_registrada <= 8) {
                                                        $temperaturaActual = 'refrigerado';
                                                    } elseif ($ruta->temperatura_registrada == -18) {
                                                        $temperaturaActual = 'congelado';
                                                    }
                                                @endphp
                                                <div class="col-md-4 col-12 mb-2 mb-md-0">
                                                    <input type="radio" class="btn-check" name="temperatura_registrada" id="temp_ambiente" value="ambiente" 
                                                           {{ $temperaturaActual == 'ambiente' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-warning w-100 temperature-btn" for="temp_ambiente">
                                                        <i class="fas fa-sun d-block mb-2 temp-icon"></i>
                                                        <strong class="d-block mb-1">Ambiente</strong>
                                                        <small class="d-block">15°C - 25°C</small>
                                                    </label>
                                                </div>
                                                <div class="col-md-4 col-12 mb-2 mb-md-0">
                                                    <input type="radio" class="btn-check" name="temperatura_registrada" id="temp_refrigerado" value="refrigerado"
                                                           {{ $temperaturaActual == 'refrigerado' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-info w-100 temperature-btn" for="temp_refrigerado">
                                                        <i class="fas fa-snowflake d-block mb-2 temp-icon"></i>
                                                        <strong class="d-block mb-1">Refrigerado</strong>
                                                        <small class="d-block">2°C - 8°C</small>
                                                    </label>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <input type="radio" class="btn-check" name="temperatura_registrada" id="temp_congelado" value="congelado"
                                                           {{ $temperaturaActual == 'congelado' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary w-100 temperature-btn" for="temp_congelado">
                                                        <i class="fas fa-icicles d-block mb-2 temp-icon"></i>
                                                        <strong class="d-block mb-1">Congelado</strong>
                                                        <small class="d-block">-18°C</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Selecciona la temperatura registrada durante el transporte
                                        </small>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label for="observaciones" class="font-weight-bold">
                                            <i class="fas fa-sticky-note text-secondary mr-2"></i>Observaciones
                                        </label>
                                        <textarea class="form-control form-control-lg" id="observaciones" name="observaciones" 
                                                  rows="3" placeholder="Observaciones adicionales sobre la ruta...">{{ $ruta->observaciones }}</textarea>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Máximo 500 caracteres
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado y Botones -->
                    <div class="row justify-content-center mt-4">
                        <div class="col-12 col-lg-10">
                            <!-- Estado del Envío -->
                            <div class="card shadow-sm border-0 modern-inner-card mb-4" style="border-left: 5px solid #6f42c1 !important;">
                                <div class="card-body p-4">
                                    <div class="form-group mb-0">
                                        <label for="estado_envio" class="font-weight-bold">
                                            <i class="fas fa-tasks text-primary mr-2"></i>Estado del Envío 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg mt-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-tasks"></i>
                                                </span>
                                            </div>
                                            <select class="form-control" id="estado_envio" name="estado_envio" required>
                                                <option value="pendiente" {{ $ruta->estado_envio == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                                <option value="en_camino" {{ $ruta->estado_envio == 'en_camino' ? 'selected' : '' }}>🚚 En Camino</option>
                                                <option value="entregado" {{ $ruta->estado_envio == 'entregado' ? 'selected' : '' }}>✅ Entregado</option>
                                                <option value="cancelado" {{ $ruta->estado_envio == 'cancelado' ? 'selected' : '' }}>❌ Cancelado</option>
                                            </select>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Al cambiar el estado del envío, el estado del transporte se actualizará automáticamente.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="text-center text-md-right mb-4">
                                <a href="{{ route('rutas.index') }}" class="btn btn-secondary btn-lg mr-2 mb-2 mb-md-0">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                                <a href="{{ route('rutas.show', $ruta->id) }}" class="btn btn-info btn-lg mr-2 mb-2 mb-md-0">
                                    <i class="fas fa-eye mr-2"></i>Ver Detalles
                                </a>
                                <button type="submit" class="btn btn-success btn-lg shadow">
                                    <i class="fas fa-save mr-2"></i>Actualizar Ruta
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</section>

<!-- Modal para Selección de Origen -->
<div class="modal fade" id="mapaOrigenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-map-marked-alt me-2"></i>Seleccionar Punto de Origen en el Mapa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mapaOrigen" style="height: 500px; border-radius: 8px; border: 2px solid #dee2e6;"></div>
                <div class="mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Dirección:</strong> <span id="origenSeleccionado">{{ $ruta->origen }}</span>
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-map-pin me-1"></i>
                                <strong>Coordenadas:</strong> <span id="origenCoordenadas">
                                    @if($ruta->origen_lat && $ruta->origen_lng)
                                        {{ $ruta->origen_lat }}, {{ $ruta->origen_lng }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-nature-warning" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-nature-success" id="btnConfirmarOrigen">Confirmar Origen</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Selección de Destino -->
<div class="modal fade" id="mapaDestinoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-flag-checkered me-2"></i>Seleccionar Punto de Destino en el Mapa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mapaDestino" style="height: 500px; border-radius: 8px; border: 2px solid #dee2e6;"></div>
                <div class="mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Dirección:</strong> <span id="destinoSeleccionado">{{ $ruta->destino }}</span>
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-map-pin me-1"></i>
                                <strong>Coordenadas:</strong> <span id="destinoCoordenadas">
                                    @if($ruta->destino_lat && $ruta->destino_lng)
                                        {{ $ruta->destino_lat }}, {{ $ruta->destino_lng }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-nature-warning" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-nature-success" id="btnConfirmarDestino">Confirmar Destino</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.modern-card {
    border-radius: 15px;
    overflow: hidden;
    border: none !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    margin: 0 auto;
    max-width: 100%;
}

.modern-card .card-body {
    padding: 30px 40px !important;
}

@media (max-width: 768px) {
    .modern-card .card-body {
        padding: 20px 15px !important;
    }
}

.modern-card .card-header.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    padding: 20px 25px;
    border-bottom: none;
}

.modern-card .card-header.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
    padding: 15px 20px;
}

.modern-card .card-header.bg-gradient-info {
    background: linear-gradient(135deg, #3494E6 0%, #EC6EAD 100%) !important;
    padding: 15px 20px;
}

.modern-inner-card {
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    margin-bottom: 20px;
    margin-left: auto;
    margin-right: auto;
}

.modern-inner-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

.btn-lg {
    padding: 12px 30px;
    font-size: 1.1rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success.btn-lg {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border: none;
    box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
}

.btn-success.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(17, 153, 142, 0.6);
}

.btn-info.btn-lg {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border: none;
}

.btn-info.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(23, 162, 184, 0.4);
}
</style>
@endpush

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const form = document.getElementById('rutaForm');
    const transporteSelect = document.getElementById('transporte_id');
    const infoTransporte = document.getElementById('info-transporte');
    const fechaSalidaInput = document.getElementById('fecha_salida');
    const fechaLlegadaInput = document.getElementById('fecha_estimada_llegada');
    
    // Elementos para mapas
    const origenInput = document.getElementById('origen');
    const destinoInput = document.getElementById('destino');
    const origenLatInput = document.getElementById('origen_lat');
    const origenLngInput = document.getElementById('origen_lng');
    const destinoLatInput = document.getElementById('destino_lat');
    const destinoLngInput = document.getElementById('destino_lng');
    const selectOrigenAlmacen = document.getElementById('selectOrigenAlmacen');
    const selectDestinoAlmacen = document.getElementById('selectDestinoAlmacen');
    const btnSeleccionarOrigen = document.getElementById('btnSeleccionarOrigen');
    const btnSeleccionarDestino = document.getElementById('btnSeleccionarDestino');
    const btnConfirmarOrigen = document.getElementById('btnConfirmarOrigen');
    const btnConfirmarDestino = document.getElementById('btnConfirmarDestino');
    const origenSeleccionadoSpan = document.getElementById('origenSeleccionado');
    const destinoSeleccionadoSpan = document.getElementById('destinoSeleccionado');
    const origenCoordenadasSpan = document.getElementById('origenCoordenadas');
    const destinoCoordenadasSpan = document.getElementById('destinoCoordenadas');

    // Variables para mapas
    let mapaOrigen = null;
    let mapaDestino = null;
    let marcadorOrigen = null;
    let marcadorDestino = null;
    
    // Coordenadas de Santa Cruz, Bolivia
    const santaCruzLat = -17.8146;
    const santaCruzLng = -63.1561;
    
    // Ubicaciones seleccionadas (inicializar con valores existentes)
    let origenSeleccionado = '{{ $ruta->origen }}';
    let destinoSeleccionado = '{{ $ruta->destino }}';
    let origenLat = {{ $ruta->origen_lat ?? 'null' }};
    let origenLng = {{ $ruta->origen_lng ?? 'null' }};
    let destinoLat = {{ $ruta->destino_lat ?? 'null' }};
    let destinoLng = {{ $ruta->destino_lng ?? 'null' }};

    // Seleccionar origen desde almacén
    selectOrigenAlmacen.addEventListener('change', function() {
        if (this.value) {
            origenInput.value = this.value;
            origenSeleccionado = this.value;
            // Limpiar coordenadas cuando se selecciona desde almacén
            origenLatInput.value = '';
            origenLngInput.value = '';
            origenLat = null;
            origenLng = null;
            showAlert('Origen actualizado desde almacén: ' + this.options[this.selectedIndex].getAttribute('data-nombre'), 'success');
        }
    });

    // Seleccionar destino desde almacén
    selectDestinoAlmacen.addEventListener('change', function() {
        if (this.value) {
            destinoInput.value = this.value;
            destinoSeleccionado = this.value;
            // Limpiar coordenadas cuando se selecciona desde almacén
            destinoLatInput.value = '';
            destinoLngInput.value = '';
            destinoLat = null;
            destinoLng = null;
            showAlert('Destino actualizado desde almacén: ' + this.options[this.selectedIndex].getAttribute('data-nombre'), 'success');
        }
    });

    // Actualizar información del transporte seleccionado
    transporteSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const capacidad = selectedOption.getAttribute('data-capacidad');
            const temperatura = selectedOption.getAttribute('data-temperatura');
            infoTransporte.innerHTML = `
                <strong>Especificaciones del transporte:</strong><br>
                • Capacidad: ${capacidad}<br>
                • Control de temperatura: ${temperatura}
            `;
        } else {
            infoTransporte.textContent = 'Selecciona un transporte para ver sus especificaciones';
        }
    });

    // Inicializar mapa de Origen
    function inicializarMapaOrigen() {
        if (mapaOrigen) {
            // Si ya existe, solo actualizar el tamaño
            mapaOrigen.invalidateSize();
            return;
        }
        
        // Determinar centro inicial
        const centerLat = origenLat || santaCruzLat;
        const centerLng = origenLng || santaCruzLng;
        const zoom = origenLat ? 15 : 13;
        
        mapaOrigen = L.map('mapaOrigen').setView([centerLat, centerLng], zoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapaOrigen);
        
        // Si hay coordenadas guardadas, mostrar marcador
        if (origenLat && origenLng) {
            marcadorOrigen = L.marker([origenLat, origenLng]).addTo(mapaOrigen);
            marcadorOrigen.bindPopup('Punto de Origen Actual').openPopup();
        }
        
        // Evento de clic en el mapa
        mapaOrigen.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            origenLat = lat;
            origenLng = lng;
            
            // Eliminar marcador anterior si existe
            if (marcadorOrigen) {
                mapaOrigen.removeLayer(marcadorOrigen);
            }
            
            // Agregar nuevo marcador
            marcadorOrigen = L.marker([lat, lng]).addTo(mapaOrigen);
            marcadorOrigen.bindPopup('Punto de Origen Seleccionado').openPopup();
            
            // Actualizar UI
            origenCoordenadasSpan.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            origenSeleccionadoSpan.textContent = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            origenSeleccionado = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            
            // Obtener dirección usando geocodificación inversa
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        origenSeleccionado = data.display_name;
                        origenSeleccionadoSpan.textContent = data.display_name;
                    }
                })
                .catch(err => console.log('Error obteniendo dirección:', err));
        });
    }
    
    // Inicializar mapa de Destino
    function inicializarMapaDestino() {
        if (mapaDestino) {
            // Si ya existe, solo actualizar el tamaño
            mapaDestino.invalidateSize();
            return;
        }
        
        // Determinar centro inicial
        const centerLat = destinoLat || santaCruzLat;
        const centerLng = destinoLng || santaCruzLng;
        const zoom = destinoLat ? 15 : 13;
        
        mapaDestino = L.map('mapaDestino').setView([centerLat, centerLng], zoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapaDestino);
        
        // Si hay coordenadas guardadas, mostrar marcador
        if (destinoLat && destinoLng) {
            marcadorDestino = L.marker([destinoLat, destinoLng], {icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41]
            })}).addTo(mapaDestino);
            marcadorDestino.bindPopup('Punto de Destino Actual').openPopup();
        }
        
        // Evento de clic en el mapa
        mapaDestino.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            destinoLat = lat;
            destinoLng = lng;
            
            // Eliminar marcador anterior si existe
            if (marcadorDestino) {
                mapaDestino.removeLayer(marcadorDestino);
            }
            
            // Agregar nuevo marcador
            marcadorDestino = L.marker([lat, lng], {icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41]
            })}).addTo(mapaDestino);
            marcadorDestino.bindPopup('Punto de Destino Seleccionado').openPopup();
            
            // Actualizar UI
            destinoCoordenadasSpan.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            destinoSeleccionadoSpan.textContent = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            destinoSeleccionado = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            
            // Obtener dirección usando geocodificación inversa
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        destinoSeleccionado = data.display_name;
                        destinoSeleccionadoSpan.textContent = data.display_name;
                    }
                })
                .catch(err => console.log('Error obteniendo dirección:', err));
        });
    }

    // Mapa para Origen
    btnSeleccionarOrigen.addEventListener('click', function() {
        const mapaOrigenModal = new bootstrap.Modal(document.getElementById('mapaOrigenModal'));
        mapaOrigenModal.show();
        
        // Inicializar mapa cuando se abre el modal
        setTimeout(() => {
            inicializarMapaOrigen();
        }, 300);
    });

    btnConfirmarOrigen.addEventListener('click', function() {
        if (origenLat && origenLng) {
            origenInput.value = origenSeleccionado || `Lat: ${origenLat.toFixed(6)}, Lng: ${origenLng.toFixed(6)}`;
            origenLatInput.value = origenLat;
            origenLngInput.value = origenLng;
            const mapaOrigenModal = bootstrap.Modal.getInstance(document.getElementById('mapaOrigenModal'));
            mapaOrigenModal.hide();
            showAlert('Punto de origen actualizado correctamente con coordenadas', 'success');
        } else if (origenInput.value) {
            // Si hay valor pero no coordenadas (seleccionado desde almacén), está bien
            const mapaOrigenModal = bootstrap.Modal.getInstance(document.getElementById('mapaOrigenModal'));
            mapaOrigenModal.hide();
        } else {
            showAlert('Por favor selecciona un punto de origen en el mapa haciendo clic', 'warning');
        }
    });

    // Mapa para Destino
    btnSeleccionarDestino.addEventListener('click', function() {
        const mapaDestinoModal = new bootstrap.Modal(document.getElementById('mapaDestinoModal'));
        mapaDestinoModal.show();
        
        // Inicializar mapa cuando se abre el modal
        setTimeout(() => {
            inicializarMapaDestino();
        }, 300);
    });

    btnConfirmarDestino.addEventListener('click', function() {
        if (destinoLat && destinoLng) {
            destinoInput.value = destinoSeleccionado || `Lat: ${destinoLat.toFixed(6)}, Lng: ${destinoLng.toFixed(6)}`;
            destinoLatInput.value = destinoLat;
            destinoLngInput.value = destinoLng;
            const mapaDestinoModal = bootstrap.Modal.getInstance(document.getElementById('mapaDestinoModal'));
            mapaDestinoModal.hide();
            showAlert('Punto de destino actualizado correctamente con coordenadas', 'success');
        } else if (destinoInput.value) {
            // Si hay valor pero no coordenadas (seleccionado desde almacén), está bien
            const mapaDestinoModal = bootstrap.Modal.getInstance(document.getElementById('mapaDestinoModal'));
            mapaDestinoModal.hide();
        } else {
            showAlert('Por favor selecciona un punto de destino en el mapa haciendo clic', 'warning');
        }
    });

    // Validar que la fecha de llegada sea posterior a la de salida
    fechaSalidaInput.addEventListener('change', function() {
        if (this.value) {
            fechaLlegadaInput.min = this.value;
            if (fechaLlegadaInput.value && fechaLlegadaInput.value <= this.value) {
                fechaLlegadaInput.value = '';
                showAlert('La fecha de llegada debe ser posterior a la fecha de salida', 'warning');
            }
        }
    });

    fechaLlegadaInput.addEventListener('change', function() {
        if (fechaSalidaInput.value && this.value <= fechaSalidaInput.value) {
            showAlert('La fecha de llegada debe ser posterior a la fecha de salida', 'warning');
            this.value = '';
        }
    });

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validar ubicaciones
        if (!origenInput.value.trim()) {
            showAlert('Por favor selecciona un punto de origen en el mapa', 'warning');
            isValid = false;
        }

        if (!destinoInput.value.trim()) {
            showAlert('Por favor selecciona un punto de destino en el mapa', 'warning');
            isValid = false;
        }

        // Validar fechas
        if (!fechaSalidaInput.value || !fechaLlegadaInput.value) {
            showAlert('Por favor completa ambas fechas', 'warning');
            isValid = false;
        }

        if (fechaSalidaInput.value && fechaLlegadaInput.value && fechaLlegadaInput.value <= fechaSalidaInput.value) {
            showAlert('La fecha de llegada debe ser posterior a la fecha de salida', 'warning');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Función para mostrar alertas
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        form.prepend(alertDiv);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Estilos para los botones de temperatura
    const tempButtons = document.querySelectorAll('.temperature-selector .btn');
    tempButtons.forEach(btn => {
        btn.style.height = '60px';
        btn.style.fontSize = '0.85rem';
    });
});
</script>

<style>
.temperature-selector .temperature-btn {
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
    border-radius: 8px !important;
    white-space: normal;
    text-align: center;
    min-height: 140px;
    padding: 20px 15px !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    word-wrap: break-word;
    overflow: hidden;
}

.temperature-selector .temperature-btn .temp-icon {
    font-size: 2rem;
    margin-bottom: 10px;
}

.temperature-selector .temperature-btn strong {
    font-size: 1rem;
    margin-bottom: 5px;
    word-break: break-word;
}

.temperature-selector .temperature-btn small {
    font-size: 0.85rem;
    word-break: break-word;
}

.temperature-selector .btn-check:checked + .temperature-btn {
    border-color: var(--verde-bosque) !important;
    background-color: var(--verde-claro) !important;
    color: var(--marron-tierra) !important;
    font-weight: bold;
    box-shadow: 0 4px 12px rgba(46, 139, 87, 0.3);
}

.temperature-selector .temperature-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    border-color: var(--verde-hoja) !important;
}

/* Responsive para selector de temperatura */
@media (max-width: 768px) {
    .temperature-selector .temperature-btn {
        margin-bottom: 10px;
        padding: 15px 10px !important;
        min-height: 120px;
    }
    
    .temperature-selector .temperature-btn .temp-icon {
        font-size: 1.5rem;
        margin-bottom: 8px;
    }
    
    .temperature-selector .temperature-btn strong {
        font-size: 0.9rem;
    }
    
    .temperature-selector .temperature-btn small {
        font-size: 0.75rem;
    }
    
    .temperature-selector .col-md-4 {
        margin-bottom: 10px;
    }
    
    .modern-inner-card .card-body {
        padding: 20px 15px !important;
    }
    
    .input-group-lg .form-control,
    .input-group-lg .form-control select {
        height: 45px;
        font-size: 0.95rem;
    }
}
</style>
@endpush