@extends('layouts.app')

@section('title', 'Editar Transporte - Del Campo')

@section('content-header', 'Editar Transporte')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('transportes.index') }}">Transportes</a></li>
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
                            <i class="fas fa-edit mr-2"></i>Editar Transporte: {{ $transporte->placa_vehiculo }}
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
            <div class="card-body p-4">
                <form action="{{ route('transportes.update', $transporte->id) }}" method="POST" id="transporteForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row justify-content-center">
                        <!-- Información del Vehículo -->
                        <div class="col-12 col-lg-10 mb-4">
                            <div class="card shadow-sm border-0 modern-inner-card" style="border-left: 5px solid #28a745 !important;">
                                <div class="card-header bg-gradient-success text-white">
                                    <h4 class="card-title mb-0">
                                        <i class="fas fa-truck mr-2"></i>Información del Vehículo
                                    </h4>
                                </div>
                                <div class="card-body p-4">
                                    <div class="form-group">
                                        <label for="placa_vehiculo" class="font-weight-bold">
                                            <i class="fas fa-id-card text-primary mr-2"></i>Placa del Vehículo 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-id-card"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="placa_vehiculo" name="placa_vehiculo" 
                                                   value="{{ $transporte->placa_vehiculo }}" required 
                                                   maxlength="10" style="text-transform:uppercase"
                                                   placeholder="ABC-123">
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Ingresa la placa del vehículo (máx. 10 caracteres)
                                        </small>
                                        <div class="invalid-feedback">Por favor ingresa una placa válida.</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="conductor" class="font-weight-bold">
                                            <i class="fas fa-user text-success mr-2"></i>Nombre del Conductor 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-success text-white">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="conductor" name="conductor" 
                                                   value="{{ $transporte->conductor }}" required 
                                                   pattern="[A-Za-záéíóúñÑ\s]+" title="Solo se permiten letras y espacios"
                                                   placeholder="Nombre del conductor">
                                        </div>
                                        <div class="invalid-feedback">Por favor ingresa un nombre válido (solo letras y espacios).</div>
                                    </div>

                                    <!-- Capacidad de Carga -->
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-weight text-info mr-2"></i>Capacidad de Carga 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="row">
                                            <div class="col-md-7 col-12 mb-2 mb-md-0">
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-info text-white">
                                                            <i class="fas fa-weight"></i>
                                                        </span>
                                                    </div>
                                                    <input type="number" step="0.01" class="form-control" id="capacidad_carga" name="capacidad_carga" 
                                                           value="{{ $transporte->capacidad_carga }}" required min="0" 
                                                           pattern="[0-9.,]+" title="Solo se permiten números"
                                                           placeholder="1000">
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-12">
                                                <select class="form-control" id="unidad_carga" name="unidad_carga" required style="height: 50px;">
                                                    <option value="kg" {{ $transporte->unidad_carga == 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                                                    <option value="ton" {{ $transporte->unidad_carga == 'ton' ? 'selected' : '' }}>Toneladas (ton)</option>
                                                    <option value="m3" {{ $transporte->unidad_carga == 'm3' ? 'selected' : '' }}>Metros Cúbicos (m³)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted mt-2" id="capacidad-ejemplo">
                                            <i class="fas fa-info-circle mr-1"></i>Ej: {{ $transporte->capacidad_carga }} {{ $transporte->unidad_carga == 'kg' ? 'kilogramos' : ($transporte->unidad_carga == 'ton' ? 'toneladas' : 'metros cúbicos') }}
                                        </small>
                                        <div class="invalid-feedback">Por favor ingresa una capacidad válida.</div>
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
                                    <!-- Selector de Temperatura -->
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">
                                            <i class="fas fa-thermometer-half text-warning mr-2"></i>Control de Temperatura 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="temperature-selector">
                                            <div class="row">
                                                <div class="col-md-4 col-12 mb-2 mb-md-0">
                                                    <input type="radio" class="btn-check" name="tipo_temperatura" id="temp_ambiente" value="ambiente" 
                                                           {{ $transporte->tipo_temperatura == 'ambiente' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-warning w-100 temperature-btn" for="temp_ambiente">
                                                        <i class="fas fa-sun d-block mb-2 temp-icon"></i>
                                                        <strong class="d-block mb-1">Ambiente</strong>
                                                        <small class="d-block">15°C - 25°C</small>
                                                    </label>
                                                </div>
                                                <div class="col-md-4 col-12 mb-2 mb-md-0">
                                                    <input type="radio" class="btn-check" name="tipo_temperatura" id="temp_refrigerado" value="refrigerado"
                                                           {{ $transporte->tipo_temperatura == 'refrigerado' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-info w-100 temperature-btn" for="temp_refrigerado">
                                                        <i class="fas fa-snowflake d-block mb-2 temp-icon"></i>
                                                        <strong class="d-block mb-1">Refrigerado</strong>
                                                        <small class="d-block">2°C - 8°C</small>
                                                    </label>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <input type="radio" class="btn-check" name="tipo_temperatura" id="temp_congelado" value="congelado"
                                                           {{ $transporte->tipo_temperatura == 'congelado' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary w-100 temperature-btn" for="temp_congelado">
                                                        <i class="fas fa-icicles d-block mb-2 temp-icon"></i>
                                                        <strong class="d-block mb-1">Congelado</strong>
                                                        <small class="d-block">-18°C</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Selecciona el tipo de control de temperatura del vehículo
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label for="telefono_conductor" class="font-weight-bold">
                                            <i class="fas fa-phone text-danger mr-2"></i>Teléfono del Conductor 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-danger text-white">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="telefono_conductor" name="telefono_conductor" 
                                                   value="{{ $transporte->telefono_conductor }}" required 
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
                            <!-- Estado del Transporte -->
                            <div class="card shadow-sm border-0 modern-inner-card mb-4" style="border-left: 5px solid #ffc107 !important;">
                                <div class="card-body p-4">
                                    <div class="form-group mb-0">
                                        <label for="estado" class="font-weight-bold">
                                            <i class="fas fa-circle text-warning mr-2"></i>Estado del Transporte 
                                            <span class="badge badge-danger">Requerido</span>
                                        </label>
                                        <div class="input-group input-group-lg mt-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-warning text-dark">
                                                    <i class="fas fa-circle"></i>
                                                </span>
                                            </div>
                                            <select class="form-control" id="estado" name="estado" required>
                                                <option value="disponible" {{ $transporte->estado == 'disponible' ? 'selected' : '' }}>🟢 Disponible</option>
                                                <option value="en_mantenimiento" {{ $transporte->estado == 'en_mantenimiento' ? 'selected' : '' }}>🟡 En Mantenimiento</option>
                                                <option value="en_ruta" {{ $transporte->estado == 'en_ruta' ? 'selected' : '' }}>🔵 En Ruta</option>
                                            </select>
                                        </div>
                                        <small class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>Selecciona el estado actual del transporte
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="text-center text-md-right mb-4">
                                <a href="{{ route('transportes.index') }}" class="btn btn-secondary btn-lg mr-2 mb-2 mb-md-0">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                                <a href="{{ route('transportes.show', $transporte->id) }}" class="btn btn-info btn-lg mr-2 mb-2 mb-md-0">
                                    <i class="fas fa-eye mr-2"></i>Ver Detalles
                                </a>
                                <button type="submit" class="btn btn-success btn-lg shadow">
                                    <i class="fas fa-save mr-2"></i>Actualizar Transporte
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const form = document.getElementById('transporteForm');
    const unidadCarga = document.getElementById('unidad_carga');
    const capacidadInput = document.getElementById('capacidad_carga');
    const capacidadEjemplo = document.getElementById('capacidad-ejemplo');
    const telefonoInput = document.getElementById('telefono_conductor');
    const placaInput = document.getElementById('placa_vehiculo');

    // Actualizar ejemplo de capacidad
    function actualizarEjemploCapacidad() {
        const valor = capacidadInput.value || '1000';
        const unidad = unidadCarga.value === 'kg' ? 'kilogramos' : 
                      unidadCarga.value === 'ton' ? 'toneladas' : 'metros cúbicos';
        capacidadEjemplo.textContent = `Ej: ${valor} ${unidad}`;
    }

    unidadCarga.addEventListener('change', actualizarEjemploCapacidad);
    capacidadInput.addEventListener('input', actualizarEjemploCapacidad);
    actualizarEjemploCapacidad();

    // Validación de teléfono (solo números)
    telefonoInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
    });

    // Convertir placa a mayúsculas
    placaInput.addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        let isValid = true;

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
}
</style>
@endpush