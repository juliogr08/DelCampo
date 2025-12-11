@extends('layouts.app')

@section('title', 'Crear Nueva Ruta')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-route text-success mr-2"></i>Crear Nueva Ruta
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('rutas.index') }}">Rutas</a></li>
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
                            <i class="fas fa-route mr-2"></i>Crear Nueva Ruta de Transporte
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('rutas.store') }}" method="POST" id="rutaForm" class="needs-validation" novalidate>
                            @csrf
                    
                            <div class="row">
                                <!-- Información de la Ruta -->
                                <div class="col-lg-6 col-md-12 mb-4">
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
                                                    <select class="form-control select2" id="transporte_id" name="transporte_id" 
                                                            style="width: 100%;" required>
                                                        <option value="">Seleccionar transporte...</option>
                                                        @foreach($transportes as $transporte)
                                                            <option value="{{ $transporte->id }}" 
                                                                    data-capacidad="{{ $transporte->capacidad_completa }}"
                                                                    data-temperatura="{{ $transporte->tipo_temperatura_nombre }}"
                                                                    {{ old('transporte_id') == $transporte->id ? 'selected' : '' }}>
                                                                {{ $transporte->placa_vehiculo }} - {{ $transporte->conductor }} 
                                                                ({{ $transporte->capacidad_completa }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="invalid-feedback">Por favor selecciona un transporte.</div>
                                                <div id="info-transporte" class="mt-3 p-3 bg-light rounded shadow-sm">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Selecciona un transporte para ver sus especificaciones
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="origen" class="font-weight-bold">
                                                    <i class="fas fa-map-marker-alt text-danger mr-2"></i>Punto de Origen 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-danger text-white">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                        </span>
                                                    </div>
                                                    <select class="form-control select2 @error('origen') is-invalid @enderror" 
                                                            id="origen" name="origen" style="width: 100%;" required>
                                                        <option value="">Seleccionar almacén de origen...</option>
                                                        @foreach($almacenes as $almacen)
                                                            <option value="{{ $almacen->ubicacion }}" 
                                                                    {{ old('origen') == $almacen->ubicacion ? 'selected' : '' }}>
                                                                {{ $almacen->nombre_almacen }} - {{ $almacen->ubicacion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('origen')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">Por favor selecciona un almacén de origen.</div>
                                            </div>

                                            <div class="form-group">
                                                <label for="destino" class="font-weight-bold">
                                                    <i class="fas fa-flag-checkered text-success mr-2"></i>Punto de Destino 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-success text-white">
                                                            <i class="fas fa-flag-checkered"></i>
                                                        </span>
                                                    </div>
                                                    <select class="form-control select2 @error('destino') is-invalid @enderror" 
                                                            id="destino" name="destino" style="width: 100%;" required>
                                                        <option value="">Seleccionar almacén de destino...</option>
                                                        @foreach($almacenes as $almacen)
                                                            <option value="{{ $almacen->ubicacion }}" 
                                                                    {{ old('destino') == $almacen->ubicacion ? 'selected' : '' }}>
                                                                {{ $almacen->nombre_almacen }} - {{ $almacen->ubicacion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('destino')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                                <div class="invalid-feedback">Por favor selecciona un almacén de destino.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fechas y Estado -->
                                <div class="col-lg-6 col-md-12 mb-4">
                                    <div class="card shadow-sm border-0 modern-inner-card" style="border-left: 5px solid #17a2b8 !important;">
                                        <div class="card-header bg-gradient-info text-white">
                                            <h4 class="card-title mb-0">
                                                <i class="fas fa-calendar-alt mr-2"></i>Programación y Estado
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
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="datetime-local" class="form-control" id="fecha_salida" name="fecha_salida" 
                                                           value="{{ old('fecha_salida') }}" required>
                                                </div>
                                                <small class="form-text text-muted mt-2">
                                                    <i class="fas fa-clock mr-1"></i>La fecha de salida no puede ser en el pasado
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
                                                            <i class="fas fa-calendar-check"></i>
                                                        </span>
                                                    </div>
                                                    <input type="datetime-local" class="form-control" id="fecha_estimada_llegada" name="fecha_estimada_llegada" 
                                                           value="{{ old('fecha_estimada_llegada') }}" required>
                                                </div>
                                                <small class="form-text text-muted mt-2">
                                                    <i class="fas fa-info-circle mr-1"></i>Debe ser posterior a la fecha de salida
                                                </small>
                                                <div class="invalid-feedback">Por favor ingresa una fecha y hora de llegada válida.</div>
                                            </div>

                                            <div class="form-group">
                                                <label for="estado_envio" class="font-weight-bold">
                                                    <i class="fas fa-tasks text-warning mr-2"></i>Estado del Envío 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-warning text-dark">
                                                            <i class="fas fa-tasks"></i>
                                                        </span>
                                                    </div>
                                                    <select class="form-control select2" id="estado_envio" name="estado_envio" style="width: 100%;" required>
                                                        <option value="pendiente" {{ old('estado_envio') == 'pendiente' ? 'selected' : '' }}>
                                                            ⏳ Pendiente
                                                        </option>
                                                        <option value="en_camino" {{ old('estado_envio') == 'en_camino' ? 'selected' : '' }}>
                                                            🚚 En Camino
                                                        </option>
                                                        <option value="entregado" {{ old('estado_envio') == 'entregado' ? 'selected' : '' }}>
                                                            ✅ Entregado
                                                        </option>
                                                        <option value="cancelado" {{ old('estado_envio') == 'cancelado' ? 'selected' : '' }}>
                                                            ❌ Cancelado
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="invalid-feedback">Por favor selecciona el estado del envío.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Selector de Temperatura -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow-sm border-0 modern-inner-card mb-4" style="border-left: 5px solid #ffc107 !important;">
                                        <div class="card-header bg-gradient-warning text-dark">
                                            <h4 class="card-title mb-0">
                                                <i class="fas fa-thermometer-half mr-2"></i>Temperatura Registrada
                                            </h4>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="temperature-selector">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <input type="radio" name="temperatura_registrada" id="temp_ambiente" value="ambiente" 
                                                               {{ old('temperatura_registrada', 'ambiente') == 'ambiente' ? 'checked' : '' }} required style="display: none;">
                                                        <label class="btn btn-outline-warning temperature-btn modern-temp-btn w-100" for="temp_ambiente">
                                                            <div class="temp-icon-wrapper">
                                                                <i class="fas fa-sun"></i>
                                                            </div>
                                                            <strong class="d-block mt-2">Ambiente</strong>
                                                            <small class="d-block">15°C - 25°C</small>
                                                        </label>
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <input type="radio" name="temperatura_registrada" id="temp_refrigerado" value="refrigerado"
                                                               {{ old('temperatura_registrada') == 'refrigerado' ? 'checked' : '' }} style="display: none;">
                                                        <label class="btn btn-outline-info temperature-btn modern-temp-btn w-100" for="temp_refrigerado">
                                                            <div class="temp-icon-wrapper">
                                                                <i class="fas fa-snowflake"></i>
                                                            </div>
                                                            <strong class="d-block mt-2">Refrigerado</strong>
                                                            <small class="d-block">2°C - 8°C</small>
                                                        </label>
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <input type="radio" name="temperatura_registrada" id="temp_congelado" value="congelado"
                                                               {{ old('temperatura_registrada') == 'congelado' ? 'checked' : '' }} style="display: none;">
                                                        <label class="btn btn-outline-primary temperature-btn modern-temp-btn w-100" for="temp_congelado">
                                                            <div class="temp-icon-wrapper">
                                                                <i class="fas fa-icicles"></i>
                                                            </div>
                                                            <strong class="d-block mt-2">Congelado</strong>
                                                            <small class="d-block">-18°C</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow-sm border-0 modern-inner-card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-sticky-note text-secondary mr-2"></i>Observaciones Adicionales
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="observaciones" class="font-weight-bold">
                                                    <i class="fas fa-comment-alt mr-2"></i>Notas y Observaciones
                                                </label>
                                                <textarea class="form-control form-control-lg" id="observaciones" name="observaciones" 
                                                          rows="4" placeholder="Ingresa observaciones adicionales sobre la ruta..." 
                                                          maxlength="500">{{ old('observaciones') }}</textarea>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <small class="form-text text-muted mb-0">
                                                        <i class="fas fa-info-circle mr-1"></i>Campo opcional
                                                    </small>
                                                    <small class="form-text mb-0">
                                                        <span id="char-count" class="font-weight-bold">0</span>/500 caracteres
                                                    </small>
                                                </div>
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
                                <a href="{{ route('rutas.index') }}" class="btn btn-secondary btn-lg mr-2">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                                <button type="submit" form="rutaForm" class="btn btn-success btn-lg shadow" id="btnSubmit">
                                    <i class="fas fa-save mr-2"></i>Guardar Ruta
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

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css">
@endpush

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: function() {
            return $(this).data('placeholder') || 'Seleccionar...';
        },
        allowClear: true
    });

    const form = document.getElementById('rutaForm');
    const transporteSelect = document.getElementById('transporte_id');
    const infoTransporte = document.getElementById('info-transporte');
    const fechaSalidaInput = document.getElementById('fecha_salida');
    const fechaLlegadaInput = document.getElementById('fecha_estimada_llegada');
    const origenSelect = document.getElementById('origen');
    const destinoSelect = document.getElementById('destino');
    const observacionesTextarea = document.getElementById('observaciones');
    const charCount = document.getElementById('char-count');

    // Establecer fecha mínima para la fecha de salida
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    fechaSalidaInput.min = now.toISOString().slice(0, 16);

    // Contador de caracteres para observaciones
    function updateCharCount() {
        const length = observacionesTextarea.value.length;
        charCount.textContent = length;
        if (length > 450) {
            charCount.classList.add('text-danger');
            charCount.classList.remove('text-muted');
        } else {
            charCount.classList.remove('text-danger');
            charCount.classList.add('text-muted');
        }
    }
    observacionesTextarea.addEventListener('input', updateCharCount);
    updateCharCount();

    // Actualizar información del transporte seleccionado con animación
    transporteSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const capacidad = selectedOption.getAttribute('data-capacidad');
            const temperatura = selectedOption.getAttribute('data-temperatura');
            $(infoTransporte).fadeOut(200, function() {
                $(this).html(`
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Especificaciones del transporte:</strong><br>
                        <i class="fas fa-box mr-1"></i> Capacidad: <strong>${capacidad}</strong><br>
                        <i class="fas fa-thermometer-half mr-1"></i> Control de temperatura: <strong>${temperatura}</strong>
                    </div>
                `).fadeIn(200);
            });
        } else {
            $(infoTransporte).fadeOut(200, function() {
                $(this).html(`
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Selecciona un transporte para ver sus especificaciones
                    </small>
                `).fadeIn(200);
            });
        }
    });

    // Validación en tiempo real de fechas
    function validateDates() {
        if (fechaSalidaInput.value && fechaLlegadaInput.value) {
            if (fechaLlegadaInput.value <= fechaSalidaInput.value) {
                fechaLlegadaInput.setCustomValidity('La fecha de llegada debe ser posterior a la fecha de salida');
                fechaLlegadaInput.classList.add('is-invalid');
                return false;
            } else {
                fechaLlegadaInput.setCustomValidity('');
                fechaLlegadaInput.classList.remove('is-invalid');
                fechaLlegadaInput.classList.add('is-valid');
            }
        }
        return true;
    }

    fechaSalidaInput.addEventListener('change', function() {
        if (this.value) {
            fechaLlegadaInput.min = this.value;
            if (fechaLlegadaInput.value && fechaLlegadaInput.value <= this.value) {
                fechaLlegadaInput.value = '';
                showToast('La fecha de llegada debe ser posterior a la fecha de salida', 'warning');
            }
        }
        validateDates();
    });

    fechaLlegadaInput.addEventListener('change', function() {
        validateDates();
        if (!validateDates()) {
            showToast('La fecha de llegada debe ser posterior a la fecha de salida', 'warning');
        }
    });

    // Validación de origen y destino diferentes
    function validateOrigenDestino() {
        if (origenSelect.value && destinoSelect.value && origenSelect.value === destinoSelect.value) {
            destinoSelect.setCustomValidity('El destino debe ser diferente al origen');
            destinoSelect.classList.add('is-invalid');
            return false;
        } else {
            destinoSelect.setCustomValidity('');
            destinoSelect.classList.remove('is-invalid');
            if (destinoSelect.value) {
                destinoSelect.classList.add('is-valid');
            }
        }
        return true;
    }

    origenSelect.addEventListener('change', function() {
        if (this.value) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
        }
        validateOrigenDestino();
    });

    destinoSelect.addEventListener('change', function() {
        validateOrigenDestino();
    });

    // Validación en tiempo real de todos los campos
    const inputs = form.querySelectorAll('input, select, textarea');
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

        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Validación completa del formulario antes de enviar
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity() || !validateDates() || !validateOrigenDestino()) {
            e.preventDefault();
            e.stopPropagation();
            showToast('Por favor corrige los errores en el formulario', 'danger');
        }
        form.classList.add('was-validated');
    });

    // Función para mostrar notificaciones toast
    function showToast(message, type = 'info') {
        const toast = $(`
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
                <div class="toast-header bg-${type} text-white">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong class="mr-auto">Notificación</strong>
                    <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">${message}</div>
            </div>
        `);
        $('body').append(toast);
        toast.toast('show');
        toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }

    // Manejar estados activos del selector de temperatura
    $('input[type="radio"][name="temperatura_registrada"]').on('change', function() {
        $('.modern-temp-btn').removeClass('active');
        $(this).next('label.modern-temp-btn').addClass('active');
    });

    // Inicializar estado activo al cargar
    $('input[type="radio"][name="temperatura_registrada"]:checked').each(function() {
        $(this).next('label.modern-temp-btn').addClass('active');
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

.modern-card .card-header.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
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
.input-group-lg .form-control,
.input-group-lg .select2-container--bootstrap4 .select2-selection {
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
    margin: 0 10px;
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
#temp_ambiente:checked ~ label.modern-temp-btn,
label.modern-temp-btn:has(#temp_ambiente:checked) {
    border-color: #ffc107 !important;
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%) !important;
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4) !important;
}

#temp_ambiente:checked ~ label.modern-temp-btn .temp-icon-wrapper,
label.modern-temp-btn:has(#temp_ambiente:checked) .temp-icon-wrapper {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
}

#temp_ambiente:checked ~ label.modern-temp-btn .temp-icon-wrapper i,
label.modern-temp-btn:has(#temp_ambiente:checked) .temp-icon-wrapper i {
    color: white;
}

#temp_refrigerado:checked ~ label.modern-temp-btn,
label.modern-temp-btn:has(#temp_refrigerado:checked) {
    border-color: #17a2b8 !important;
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%) !important;
    box-shadow: 0 10px 25px rgba(23, 162, 184, 0.4) !important;
}

#temp_refrigerado:checked ~ label.modern-temp-btn .temp-icon-wrapper,
label.modern-temp-btn:has(#temp_refrigerado:checked) .temp-icon-wrapper {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

#temp_refrigerado:checked ~ label.modern-temp-btn .temp-icon-wrapper i,
label.modern-temp-btn:has(#temp_refrigerado:checked) .temp-icon-wrapper i {
    color: white;
}

#temp_congelado:checked ~ label.modern-temp-btn,
label.modern-temp-btn:has(#temp_congelado:checked) {
    border-color: #007bff !important;
    background: linear-gradient(135deg, #cce5ff 0%, #99ccff 100%) !important;
    box-shadow: 0 10px 25px rgba(0, 123, 255, 0.4) !important;
}

#temp_congelado:checked ~ label.modern-temp-btn .temp-icon-wrapper,
label.modern-temp-btn:has(#temp_congelado:checked) .temp-icon-wrapper {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

#temp_congelado:checked ~ label.modern-temp-btn .temp-icon-wrapper i,
label.modern-temp-btn:has(#temp_congelado:checked) .temp-icon-wrapper i {
    color: white;
}

/* JavaScript para manejar estados activos */
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

/* Select2 Personalizado Mejorado */
.select2-container--bootstrap4 .select2-selection {
    border: 2px solid #ced4da;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.select2-container--bootstrap4 .select2-selection:hover {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.1);
}

.select2-container--bootstrap4 .select2-selection--single {
    height: 50px;
}

.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    line-height: 50px;
    padding-left: 15px;
    font-size: 1rem;
}

/* Info Transporte Mejorado */
#info-transporte {
    border-left: 4px solid #17a2b8;
    background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Responsive Ultra Mejorado */
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

/* Select2 personalizado */
.select2-container--bootstrap4 .select2-selection {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.select2-container--bootstrap4 .select2-selection--single {
    height: calc(2.25rem + 2px);
}

.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    line-height: calc(2.25rem + 2px);
}

/* Toast notifications */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
}
</style>
@endsection
