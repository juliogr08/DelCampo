@extends('layouts.app')

@section('title', 'Editar Almacén - Proven')

@section('content-header', 'Editar Almacén')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
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
                            <i class="fas fa-edit mr-2"></i>Editar Almacén: {{ $almacen->nombre_almacen }}
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
            <div class="card-body p-4">
                <form action="{{ route('almacenes.update', $almacen->id) }}" method="POST" id="almacenForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row justify-content-center">
                        <!-- Información Básica -->
                        <div class="col-12 col-lg-10 mb-4">
                            <div class="card shadow-sm border-0 modern-inner-card" style="border-left: 5px solid #28a745 !important;">
                                <div class="card-header bg-gradient-success text-white">
                                    <h4 class="card-title mb-0">
                                        <i class="fas fa-warehouse mr-2"></i>Información Básica
                                    </h4>
                                </div>
                                <div class="card-body p-4">
                            
                                    <div class="form-group">
                                        <label for="nombre_almacen" class="font-weight-bold">
                                            <i class="fas fa-tag text-primary mr-2"></i>Nombre del Almacén 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-tag"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="nombre_almacen" name="nombre_almacen" 
                                                   value="{{ $almacen->nombre_almacen }}" required 
                                                   maxlength="255" placeholder="Nombre del almacén">
                                        </div>
                                        <div class="invalid-feedback">Por favor ingresa un nombre válido para el almacén.</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="ubicacion" class="font-weight-bold">
                                            <i class="fas fa-map-marker-alt text-info mr-2"></i>Ubicación 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-info text-white">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" 
                                                   value="{{ $almacen->ubicacion }}" required 
                                                   placeholder="Selecciona la ubicación en el mapa"
                                                   readonly>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-info" id="btnSeleccionarMapa">
                                                    <i class="fas fa-map mr-1"></i>Cambiar
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Haz clic en el botón para seleccionar la ubicación exacta
                                        </small>
                                        <div class="invalid-feedback">Por favor selecciona una ubicación en el mapa.</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="responsable" class="font-weight-bold">
                                            <i class="fas fa-user text-success mr-2"></i>Responsable 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-success text-white">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="responsable" name="responsable" 
                                                   value="{{ $almacen->responsable }}" required 
                                                   pattern="[A-Za-záéíóúñÑ\s]+" title="Solo se permiten letras y espacios"
                                                   placeholder="Nombre del responsable">
                                        </div>
                                        <div class="invalid-feedback">Por favor ingresa un nombre válido (solo letras y espacios).</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Especificaciones -->
                        <div class="col-12 col-lg-10 mb-4">
                            <div class="card shadow-sm border-0 modern-inner-card" style="border-left: 5px solid #17a2b8 !important;">
                                <div class="card-header bg-gradient-info text-white">
                                    <h4 class="card-title mb-0">
                                        <i class="fas fa-cogs mr-2"></i>Especificaciones
                                    </h4>
                                </div>
                                <div class="card-body p-4">
                                    <!-- Capacidad con Selector de Unidad -->
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-arrows-alt text-info mr-2"></i>Capacidad del Almacén 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="row">
                                            <div class="col-md-7 col-12 mb-2 mb-md-0">
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-info text-white">
                                                            <i class="fas fa-arrows-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="number" step="0.01" class="form-control" id="capacidad" name="capacidad" 
                                                           value="{{ $almacen->capacidad }}" required min="0" 
                                                           pattern="[0-9.,]+" title="Solo se permiten números"
                                                           placeholder="100">
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-12">
                                                <select class="form-control" id="unidad_capacidad" name="unidad_capacidad" required style="height: 50px;">
                                                    <option value="m2" {{ ($almacen->unidad_capacidad ?? 'm2') == 'm2' ? 'selected' : '' }}>Metros Cuadrados (m²)</option>
                                                    <option value="hectareas" {{ ($almacen->unidad_capacidad ?? '') == 'hectareas' ? 'selected' : '' }}>Hectáreas (ha)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted mt-2" id="capacidad-ejemplo">
                                            <i class="fas fa-info-circle mr-1"></i>Ej: {{ $almacen->capacidad ?? '100' }} {{ ($almacen->unidad_capacidad ?? 'm2') == 'm2' ? 'metros cuadrados' : 'hectáreas' }}
                                        </small>
                                        <div class="invalid-feedback">Por favor ingresa una capacidad válida.</div>
                                    </div>

                                    <!-- Selector de Temperatura Mejorado -->
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-thermometer-half text-warning mr-2"></i>Tipo de Almacenamiento
                                        </label>
                                        <div class="temperature-selector">
                                            <div class="row">
                                                <div class="col-md-4 col-12 mb-2 mb-md-0">
                                                    <input type="radio" class="btn-check" name="tipo_almacenamiento" id="tipo_ambiente" value="ambiente" 
                                                           {{ ($almacen->tipo_almacenamiento ?? 'ambiente') == 'ambiente' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-warning w-100 temperature-btn" for="tipo_ambiente">
                                                        <i class="fas fa-sun d-block mb-2 temp-icon"></i>
                                                        <strong class="d-block mb-1">Ambiente</strong>
                                                        <small class="d-block">15°C - 25°C</small>
                                                    </label>
                                                </div>
                                                <div class="col-md-4 col-12 mb-2 mb-md-0">
                                                    <input type="radio" class="btn-check" name="tipo_almacenamiento" id="tipo_refrigerado" value="refrigerado"
                                                           {{ ($almacen->tipo_almacenamiento ?? '') == 'refrigerado' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-info w-100 temperature-btn" for="tipo_refrigerado">
                                                        <i class="fas fa-snowflake d-block mb-2 temp-icon"></i>
                                                        <strong class="d-block mb-1">Refrigerado</strong>
                                                        <small class="d-block">2°C - 8°C</small>
                                                    </label>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <input type="radio" class="btn-check" name="tipo_almacenamiento" id="tipo_congelado" value="congelado"
                                                           {{ ($almacen->tipo_almacenamiento ?? '') == 'congelado' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary w-100 temperature-btn" for="tipo_congelado">
                                                        <i class="fas fa-icicles d-block mb-2 temp-icon"></i>
                                                        <strong class="d-block mb-1">Congelado</strong>
                                                        <small class="d-block">-18°C</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Selecciona el tipo de almacenamiento según los productos
                                        </small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="telefono_contacto" class="font-weight-bold">
                                            <i class="fas fa-phone text-danger mr-2"></i>Teléfono de Contacto 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-danger text-white">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="telefono_contacto" name="telefono_contacto" 
                                                   value="{{ $almacen->telefono_contacto }}" required 
                                                   pattern="[0-9]{8}" title="Debe contener exactamente 8 dígitos numéricos"
                                                   maxlength="8" placeholder="12345678">
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Debe contener exactamente 8 dígitos
                                        </small>
                                        <div class="invalid-feedback">Por favor ingresa un número de teléfono válido (8 dígitos).</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado y Botones -->
                    <div class="row justify-content-center mt-4">
                        <div class="col-12 col-lg-10">
                            <!-- Estado -->
                            <div class="card shadow-sm border-0 modern-inner-card mb-4" style="border-left: 5px solid #ffc107 !important;">
                                <div class="card-body p-4">
                                    <div class="form-group mb-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" 
                                                   {{ $almacen->activo ? 'checked' : '' }}
                                                   style="width: 3rem; height: 1.5rem;">
                                            <label class="form-check-label font-weight-bold" for="activo" style="font-size: 1.1rem; margin-left: 10px;">
                                                <i class="fas fa-power-off me-2 text-success"></i>Almacén Activo
                                            </label>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Desactiva este interruptor si el almacén no está operativo
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="text-center text-md-right mb-4">
                                <a href="{{ route('almacenes.index') }}" class="btn btn-secondary btn-lg mr-2 mb-2 mb-md-0">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                                <a href="{{ route('almacenes.show', $almacen->id) }}" class="btn btn-info btn-lg mr-2 mb-2 mb-md-0">
                                    <i class="fas fa-eye mr-2"></i>Ver Detalles
                                </a>
                                <button type="submit" class="btn btn-success btn-lg shadow">
                                    <i class="fas fa-save mr-2"></i>Actualizar Almacén
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

<!-- Modal Simulado para el Mapa -->
<div class="modal fade" id="mapaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-map-marked-alt me-2"></i>Seleccionar Ubicación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mapaModalContent" style="height: 400px; background: linear-gradient(45deg, #e8f5e8, #c8e6c9); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt fa-3x text-success mb-3"></i>
                        <h5 class="text-success">Integración con Google Maps</h5>
                        <p class="text-muted">En una implementación real, aquí se integraría Google Maps API</p>
                        <div class="mt-3">
                            <button type="button" class="btn btn-nature-success" id="btnSimularUbicacion">
                                <i class="fas fa-location-arrow me-1"></i>Simular Ubicación
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Ubicación seleccionada: <span id="ubicacionSeleccionada">{{ $almacen->ubicacion }}</span>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-nature-warning" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-nature-success" id="btnConfirmarModal">Confirmar Ubicación</button>
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

.input-group-lg .form-control {
    height: 50px;
    font-size: 1rem;
}

.input-group-prepend .input-group-text {
    font-size: 1.1rem;
    min-width: 50px;
    justify-content: center;
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

.badge {
    padding: 5px 10px;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 20px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const ubicacionInput = document.getElementById('ubicacion');
    const btnSeleccionarMapa = document.getElementById('btnSeleccionarMapa');
    const btnSimularUbicacion = document.getElementById('btnSimularUbicacion');
    const btnConfirmarModal = document.getElementById('btnConfirmarModal');
    const ubicacionSeleccionadaSpan = document.getElementById('ubicacionSeleccionada');
    const form = document.getElementById('almacenForm');
    const unidadCapacidad = document.getElementById('unidad_capacidad');
    const capacidadInput = document.getElementById('capacidad');
    const capacidadEjemplo = document.getElementById('capacidad-ejemplo');
    const telefonoInput = document.getElementById('telefono_contacto');

    // Ubicación seleccionada
    let ubicacionSeleccionada = '{{ $almacen->ubicacion }}';

    // Actualizar ejemplo de capacidad
    function actualizarEjemploCapacidad() {
        const valor = capacidadInput.value || '100';
        const unidad = unidadCapacidad.value === 'm2' ? 'metros cuadrados' : 'hectáreas';
        capacidadEjemplo.textContent = `Ej: ${valor} ${unidad}`;
    }

    unidadCapacidad.addEventListener('change', actualizarEjemploCapacidad);
    capacidadInput.addEventListener('input', actualizarEjemploCapacidad);
    actualizarEjemploCapacidad();

    // Validación de teléfono (solo números)
    telefonoInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
    });

    // Simulación de selección de mapa
    btnSeleccionarMapa.addEventListener('click', function() {
        const mapaModal = new bootstrap.Modal(document.getElementById('mapaModal'));
        mapaModal.show();
    });

    // Simular selección de ubicación
    btnSimularUbicacion.addEventListener('click', function() {
        const ubicaciones = [
            'Av. Industrial #123, Zona Norte, Lima',
            'Calle Comercio #456, Centro, Arequipa', 
            'Jr. Mercado #789, Distrito Sur, Trujillo',
            'Carretera Panamericana Km 45, Callao'
        ];
        const ubicacionAleatoria = ubicaciones[Math.floor(Math.random() * ubicaciones.length)];
        ubicacionSeleccionada = ubicacionAleatoria;
        ubicacionSeleccionadaSpan.textContent = ubicacionAleatoria;
    });

    // Confirmar ubicación en el modal
    btnConfirmarModal.addEventListener('click', function() {
        if (ubicacionSeleccionada) {
            ubicacionInput.value = ubicacionSeleccionada;
            const mapaModal = bootstrap.Modal.getInstance(document.getElementById('mapaModal'));
            mapaModal.hide();
            
            // Mostrar mensaje de éxito
            showAlert('Ubicación actualizada correctamente', 'success');
        } else {
            showAlert('Por favor selecciona una ubicación en el mapa', 'warning');
        }
    });

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validar ubicación
        if (!ubicacionInput.value.trim()) {
            showAlert('Por favor selecciona una ubicación en el mapa', 'warning');
            isValid = false;
        }

        // Validar teléfono
        if (!telefonoInput.value.match(/^[0-9]{8}$/)) {
            showAlert('El teléfono debe contener exactamente 8 dígitos', 'warning');
            isValid = false;
        }

        // Validar capacidad
        if (!capacidadInput.value || parseFloat(capacidadInput.value) <= 0) {
            showAlert('Por favor ingresa una capacidad válida', 'warning');
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
    
    .modern-card .card-body {
        padding: 20px 15px !important;
    }
}
</style>
@endpush