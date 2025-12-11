@extends('layouts.app')

@section('title', 'Crear Nuevo Transporte')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-truck text-success mr-2"></i>Crear Nuevo Transporte
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('transportes.index') }}">Transportes</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="border-left: 5px solid #dc3545;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> <strong>Errores de validación:</strong></h5>
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <!-- Card Principal con diseño moderno -->
                <div class="card card-primary card-outline shadow-lg modern-card">
                    <div class="card-header bg-gradient-primary">
                        <h3 class="card-title text-white">
                            <i class="fas fa-truck mr-2"></i>Crear Nuevo Transporte
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('transportes.store') }}" method="POST" id="transporteForm" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="row">
                                <!-- Información del Vehículo -->
                                <div class="col-lg-6 col-md-12 mb-4">
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
                                                           value="{{ old('placa_vehiculo') }}" required placeholder="Ej: ABC-123"
                                                           maxlength="10" style="text-transform:uppercase">
                                                </div>
                                                <small class="form-text text-muted mt-2">
                                                    <i class="fas fa-info-circle mr-1"></i>Ingresa la placa del vehículo (máx. 10 caracteres)
                                                </small>
                                                <div class="invalid-feedback">Por favor ingresa una placa válida.</div>
                                            </div>

                                            <div class="form-group">
                                                <label for="conductor" class="font-weight-bold">
                                                    <i class="fas fa-user text-info mr-2"></i>Nombre del Conductor 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-info text-white">
                                                            <i class="fas fa-user"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" id="conductor" name="conductor" 
                                                           value="{{ old('conductor') }}" required placeholder="Ej: Carlos Rodríguez"
                                                           pattern="[A-Za-záéíóúñÑ\s]+" title="Solo se permiten letras y espacios">
                                                </div>
                                                <div class="invalid-feedback">Por favor ingresa un nombre válido (solo letras y espacios).</div>
                                            </div>

                                            <!-- Capacidad de Carga -->
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    <i class="fas fa-weight text-warning mr-2"></i>Capacidad de Carga 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="row">
                                                    <div class="col-7">
                                                        <div class="input-group input-group-lg">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-warning text-dark">
                                                                    <i class="fas fa-weight"></i>
                                                                </span>
                                                            </div>
                                                            <input type="number" step="0.01" class="form-control" id="capacidad_carga" name="capacidad_carga" 
                                                                   value="{{ old('capacidad_carga') }}" required min="0" placeholder="0.00" pattern="[0-9.,]+"
                                                                   title="Solo se permiten números">
                                                        </div>
                                                    </div>
                                                    <div class="col-5">
                                                        <select class="form-control" id="unidad_carga" name="unidad_carga" required style="height: 50px;">
                                                            <option value="kg" {{ old('unidad_carga', 'kg') == 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                                                            <option value="ton" {{ old('unidad_carga') == 'ton' ? 'selected' : '' }}>Toneladas (ton)</option>
                                                            <option value="m3" {{ old('unidad_carga') == 'm3' ? 'selected' : '' }}>Metros Cúbicos (m³)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <small class="form-text text-muted mt-2" id="capacidad-ejemplo">Ej: 1000 kilogramos</small>
                                                <div class="invalid-feedback">Por favor ingresa una capacidad válida.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Especificaciones -->
                                <div class="col-lg-6 col-md-12 mb-4">
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
                                                        <div class="col-md-4 mb-3">
                                                            <input type="radio" name="tipo_temperatura" id="temp_ambiente_trans" value="ambiente" 
                                                                   {{ old('tipo_temperatura', 'ambiente') == 'ambiente' ? 'checked' : '' }} style="display: none;">
                                                            <label class="btn btn-outline-warning temperature-btn modern-temp-btn w-100" for="temp_ambiente_trans">
                                                                <div class="temp-icon-wrapper">
                                                                    <i class="fas fa-sun"></i>
                                                                </div>
                                                                <strong class="d-block mt-2">Ambiente</strong>
                                                                <small class="d-block">15°C - 25°C</small>
                                                            </label>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <input type="radio" name="tipo_temperatura" id="temp_refrigerado_trans" value="refrigerado"
                                                                   {{ old('tipo_temperatura') == 'refrigerado' ? 'checked' : '' }} style="display: none;">
                                                            <label class="btn btn-outline-info temperature-btn modern-temp-btn w-100" for="temp_refrigerado_trans">
                                                                <div class="temp-icon-wrapper">
                                                                    <i class="fas fa-snowflake"></i>
                                                                </div>
                                                                <strong class="d-block mt-2">Refrigerado</strong>
                                                                <small class="d-block">2°C - 8°C</small>
                                                            </label>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <input type="radio" name="tipo_temperatura" id="temp_congelado_trans" value="congelado"
                                                                   {{ old('tipo_temperatura') == 'congelado' ? 'checked' : '' }} style="display: none;">
                                                            <label class="btn btn-outline-primary temperature-btn modern-temp-btn w-100" for="temp_congelado_trans">
                                                                <div class="temp-icon-wrapper">
                                                                    <i class="fas fa-icicles"></i>
                                                                </div>
                                                                <strong class="d-block mt-2">Congelado</strong>
                                                                <small class="d-block">-18°C</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <small class="form-text text-muted mt-2">
                                                    <i class="fas fa-info-circle mr-1"></i>Selecciona el tipo de control de temperatura del vehículo
                                                </small>
                                            </div>

                                            <!-- Estado del Transporte -->
                                            <div class="form-group">
                                                <label for="estado" class="font-weight-bold">
                                                    <i class="fas fa-circle text-success mr-2"></i>Estado del Transporte 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-success text-white">
                                                            <i class="fas fa-circle"></i>
                                                        </span>
                                                    </div>
                                                    <select class="form-control" id="estado" name="estado" required>
                                                        <option value="disponible" {{ old('estado', 'disponible') == 'disponible' ? 'selected' : '' }}>🟢 Disponible</option>
                                                        <option value="en_mantenimiento" {{ old('estado') == 'en_mantenimiento' ? 'selected' : '' }}>🟡 En Mantenimiento</option>
                                                        <option value="en_ruta" {{ old('estado') == 'en_ruta' ? 'selected' : '' }}>🔵 En Ruta</option>
                                                    </select>
                                                </div>
                                                <small class="form-text text-muted mt-2">
                                                    <i class="fas fa-info-circle mr-1"></i>Selecciona el estado actual del transporte
                                                </small>
                                                <div class="invalid-feedback">Por favor selecciona el estado del transporte.</div>
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
                                                           value="{{ old('telefono_conductor') }}" required placeholder="Ej: 98765432"
                                                           pattern="[0-9]{8}" title="Debe contener exactamente 8 dígitos numéricos"
                                                           maxlength="8">
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
                        </form>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ route('transportes.index') }}" class="btn btn-secondary btn-lg mr-2">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                                <button type="submit" form="transporteForm" class="btn btn-success btn-lg shadow" id="btnSubmit">
                                    <i class="fas fa-save mr-2"></i>Guardar Transporte
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
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

    // Manejar estados activos del selector de temperatura
    $('input[type="radio"][name="tipo_temperatura"]').on('change', function() {
        $('.modern-temp-btn').removeClass('active');
        $(this).next('label.modern-temp-btn').addClass('active');
    });

    // Inicializar estado activo al cargar
    $('input[type="radio"][name="tipo_temperatura"]:checked').each(function() {
        $(this).next('label.modern-temp-btn').addClass('active');
    });

    // Validación en tiempo real
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validar teléfono
        if (!telefonoInput.value.match(/^[0-9]{8}$/)) {
            telefonoInput.classList.add('is-invalid');
            isValid = false;
        }

        // Validar capacidad
        if (!capacidadInput.value || parseFloat(capacidadInput.value) <= 0) {
            capacidadInput.classList.add('is-invalid');
            isValid = false;
        }

        if (!form.checkValidity() || !isValid) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>

<style>
/* ============================================
   DISEÑO MODERNO Y VISUALMENTE ATRACTIVO
   ============================================ */

/* Card Principal */
.modern-card {
    border-radius: 15px;
    overflow: hidden;
    border: none !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
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

/* Cards Internas Modernas */
.modern-inner-card {
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    margin-bottom: 20px;
}

.modern-inner-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

.modern-inner-card .card-header {
    border-radius: 12px 12px 0 0;
    border-bottom: 2px solid rgba(255,255,255,0.2);
}

/* Input Groups Mejorados */
.input-group-lg .form-control {
    height: 50px;
    font-size: 1rem;
    border-radius: 0 8px 8px 0;
}

.input-group-prepend .input-group-text {
    border-radius: 8px 0 0 8px;
    font-size: 1.1rem;
    min-width: 50px;
    justify-content: center;
}

/* Selector de Temperatura Ultra Moderno */
.temperature-selector {
    padding: 10px 0;
}

.modern-temp-btn {
    border: 3px solid #e9ecef !important;
    border-radius: 15px !important;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 180px;
    padding: 20px 15px;
    position: relative;
    overflow: hidden;
    background: white;
}

.modern-temp-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.modern-temp-btn:hover::before {
    left: 100%;
}

.modern-temp-btn:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important;
    border-width: 4px !important;
}

.temp-icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto 15px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    transition: all 0.3s ease;
}

.modern-temp-btn:hover .temp-icon-wrapper {
    transform: rotate(360deg) scale(1.1);
}

.temp-icon-wrapper i {
    font-size: 2.5rem;
    color: #495057;
}

/* Estados activos del selector de temperatura */
#temp_ambiente_trans:checked ~ label.modern-temp-btn,
label.modern-temp-btn:has(#temp_ambiente_trans:checked) {
    border-color: #ffc107 !important;
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%) !important;
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4) !important;
}

#temp_ambiente_trans:checked ~ label.modern-temp-btn .temp-icon-wrapper,
label.modern-temp-btn:has(#temp_ambiente_trans:checked) .temp-icon-wrapper {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
}

#temp_ambiente_trans:checked ~ label.modern-temp-btn .temp-icon-wrapper i,
label.modern-temp-btn:has(#temp_ambiente_trans:checked) .temp-icon-wrapper i {
    color: white;
}

#temp_refrigerado_trans:checked ~ label.modern-temp-btn,
label.modern-temp-btn:has(#temp_refrigerado_trans:checked) {
    border-color: #17a2b8 !important;
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%) !important;
    box-shadow: 0 10px 25px rgba(23, 162, 184, 0.4) !important;
}

#temp_refrigerado_trans:checked ~ label.modern-temp-btn .temp-icon-wrapper,
label.modern-temp-btn:has(#temp_refrigerado_trans:checked) .temp-icon-wrapper {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

#temp_refrigerado_trans:checked ~ label.modern-temp-btn .temp-icon-wrapper i,
label.modern-temp-btn:has(#temp_refrigerado_trans:checked) .temp-icon-wrapper i {
    color: white;
}

#temp_congelado_trans:checked ~ label.modern-temp-btn,
label.modern-temp-btn:has(#temp_congelado_trans:checked) {
    border-color: #007bff !important;
    background: linear-gradient(135deg, #cce5ff 0%, #99ccff 100%) !important;
    box-shadow: 0 10px 25px rgba(0, 123, 255, 0.4) !important;
}

#temp_congelado_trans:checked ~ label.modern-temp-btn .temp-icon-wrapper,
label.modern-temp-btn:has(#temp_congelado_trans:checked) .temp-icon-wrapper {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

#temp_congelado_trans:checked ~ label.modern-temp-btn .temp-icon-wrapper i,
label.modern-temp-btn:has(#temp_congelado_trans:checked) .temp-icon-wrapper i {
    color: white;
}

.modern-temp-btn.active {
    border-width: 4px !important;
    font-weight: bold;
}

.modern-temp-btn.active .temp-icon-wrapper {
    transform: scale(1.15);
}

/* Validación Visual Mejorada */
.form-control.is-valid {
    border: 2px solid #28a745 !important;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.form-control.is-invalid {
    border: 2px solid #dc3545 !important;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3cpath d='M6 7v2'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    animation: shake 0.5s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}

/* Badges Modernos */
.badge {
    padding: 5px 10px;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 20px;
}

/* Botones Mejorados */
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

.btn-secondary.btn-lg {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
}

/* Labels Mejorados */
label.font-weight-bold {
    font-size: 1rem;
    color: #495057;
    margin-bottom: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .modern-temp-btn {
        height: 150px;
        margin: 5px;
        padding: 15px 10px;
    }
    
    .temp-icon-wrapper {
        width: 60px;
        height: 60px;
    }
    
    .temp-icon-wrapper i {
        font-size: 2rem;
    }
    
    .modern-card .card-header {
        padding: 15px !important;
    }
    
    .card-body {
        padding: 20px !important;
    }
    
    .btn-lg {
        width: 100%;
        margin-bottom: 10px;
    }
}

/* Animaciones Globales */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-inner-card {
    animation: fadeInUp 0.6s ease-out;
}

.modern-inner-card:nth-child(1) { animation-delay: 0.1s; }
.modern-inner-card:nth-child(2) { animation-delay: 0.2s; }
.modern-inner-card:nth-child(3) { animation-delay: 0.3s; }
</style>
@endsection
