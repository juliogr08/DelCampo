@extends('layouts.app')

@section('title', 'Crear Nuevo Producto')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-cube text-success mr-2"></i>Crear Nuevo Producto
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
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
                            <i class="fas fa-cube mr-2"></i>Crear Nuevo Producto
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('productos.store') }}" method="POST" id="productForm" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="row">
                                <!-- Información Básica -->
                                <div class="col-lg-6 col-md-12 mb-4">
                                    <div class="card shadow-sm border-0 modern-inner-card" style="border-left: 5px solid #28a745 !important;">
                                        <div class="card-header bg-gradient-success text-white">
                                            <h4 class="card-title mb-0">
                                                <i class="fas fa-cube mr-2"></i>Información Básica
                                            </h4>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="form-group">
                                                <label for="nombre" class="font-weight-bold">
                                                    <i class="fas fa-tag text-primary mr-2"></i>Nombre del Producto 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-primary text-white">
                                                            <i class="fas fa-tag"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                                           value="{{ old('nombre') }}" required placeholder="Ej: Leche Entera 1L">
                                                </div>
                                                <div class="invalid-feedback">Por favor ingresa el nombre del producto.</div>
                                            </div>

                                            <div class="form-group">
                                                <label for="categoria" class="font-weight-bold">
                                                    <i class="fas fa-folder text-info mr-2"></i>Categoría
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-info text-white">
                                                            <i class="fas fa-folder"></i>
                                                        </span>
                                                    </div>
                                                    <select class="form-control" id="categoria" name="categoria">
                                                        <option value="">Seleccionar categoría</option>
                                                        <option value="lacteos" {{ old('categoria') == 'lacteos' ? 'selected' : '' }}>🥛 Lácteos</option>
                                                        <option value="carnes" {{ old('categoria') == 'carnes' ? 'selected' : '' }}>🍖 Carnes y Pescados</option>
                                                        <option value="frutas" {{ old('categoria') == 'frutas' ? 'selected' : '' }}>🍎 Frutas y Verduras</option>
                                                        <option value="granos" {{ old('categoria') == 'granos' ? 'selected' : '' }}>🌾 Granos y Cereales</option>
                                                        <option value="bebidas" {{ old('categoria') == 'bebidas' ? 'selected' : '' }}>🥤 Bebidas</option>
                                                        <option value="congelados" {{ old('categoria') == 'congelados' ? 'selected' : '' }}>❄️ Congelados</option>
                                                        <option value="secos" {{ old('categoria') == 'secos' ? 'selected' : '' }}>📦 Productos Secos</option>
                                                        <option value="farmacia" {{ old('categoria') == 'farmacia' ? 'selected' : '' }}>💊 Farmacéuticos</option>
                                                        <option value="otros" {{ old('categoria') == 'otros' ? 'selected' : '' }}>📁 Otros</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="codigo_barras" class="font-weight-bold">
                                                    <i class="fas fa-barcode text-warning mr-2"></i>Código de Barras 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-warning text-dark">
                                                            <i class="fas fa-barcode"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" 
                                                           value="{{ old('codigo_barras') }}" required placeholder="Ej: 7501234567890">
                                                </div>
                                                <small class="form-text text-muted mt-2">
                                                    <i class="fas fa-info-circle mr-1"></i>Código único para identificar el producto
                                                </small>
                                                <div class="invalid-feedback">Por favor ingresa un código de barras válido.</div>
                                            </div>

                                            <div class="form-group">
                                                <label for="precio" class="font-weight-bold">
                                                    <i class="fas fa-dollar-sign text-success mr-2"></i>Precio Unitario 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-success text-white">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                                    </div>
                                                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" 
                                                           value="{{ old('precio') }}" required min="0" placeholder="0.00">
                                                </div>
                                                <div class="invalid-feedback">Por favor ingresa un precio válido.</div>
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
                                                    <i class="fas fa-thermometer-half text-warning mr-2"></i>Condición de Temperatura
                                                </label>
                                                <div class="temperature-selector">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <input type="radio" name="temperatura_requerida" id="temp_caliente" value="ambiente" 
                                                                   {{ old('temperatura_requerida', 'ambiente') == 'ambiente' ? 'checked' : '' }} style="display: none;">
                                                            <label class="btn btn-outline-warning temperature-btn modern-temp-btn w-100" for="temp_caliente">
                                                                <div class="temp-icon-wrapper">
                                                                    <i class="fas fa-sun"></i>
                                                                </div>
                                                                <strong class="d-block mt-2">Ambiente</strong>
                                                                <small class="d-block">15°C - 25°C</small>
                                                            </label>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <input type="radio" name="temperatura_requerida" id="temp_frio" value="5"
                                                                   {{ old('temperatura_requerida') == '5' ? 'checked' : '' }} style="display: none;">
                                                            <label class="btn btn-outline-info temperature-btn modern-temp-btn w-100" for="temp_frio">
                                                                <div class="temp-icon-wrapper">
                                                                    <i class="fas fa-snowflake"></i>
                                                                </div>
                                                                <strong class="d-block mt-2">Refrigerado</strong>
                                                                <small class="d-block">2°C - 8°C</small>
                                                            </label>
                                                        </div>

                                                        <div class="col-md-4 mb-3">
                                                            <input type="radio" name="temperatura_requerida" id="temp_congelado_prod" value="-18"
                                                                   {{ old('temperatura_requerida') == '-18' ? 'checked' : '' }} style="display: none;">
                                                            <label class="btn btn-outline-primary temperature-btn modern-temp-btn w-100" for="temp_congelado_prod">
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

                                            <!-- Peso/Volumen -->
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    <i class="fas fa-weight text-secondary mr-2"></i>Peso/Volumen
                                                </label>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="input-group input-group-lg">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-secondary text-white">
                                                                    <i class="fas fa-weight"></i>
                                                                </span>
                                                            </div>
                                                            <input type="number" step="0.001" class="form-control" id="peso" name="peso" 
                                                                   value="{{ old('peso', '1') }}" placeholder="0.000">
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <select class="form-control" id="unidad_medida" name="unidad_medida" style="height: 50px;">
                                                            <option value="kg" {{ old('unidad_medida', 'kg') == 'kg' ? 'selected' : '' }}>Kilogramos</option>
                                                            <option value="g" {{ old('unidad_medida') == 'g' ? 'selected' : '' }}>Gramos</option>
                                                            <option value="ton" {{ old('unidad_medida') == 'ton' ? 'selected' : '' }}>Toneladas</option>
                                                            <option value="lb" {{ old('unidad_medida') == 'lb' ? 'selected' : '' }}>Libras</option>
                                                            <option value="l" {{ old('unidad_medida') == 'l' ? 'selected' : '' }}>Litros</option>
                                                            <option value="ml" {{ old('unidad_medida') == 'ml' ? 'selected' : '' }}>Mililitros</option>
                                                            <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidades</option>
                                                            <option value="caja" {{ old('unidad_medida') == 'caja' ? 'selected' : '' }}>Cajas</option>
                                                            <option value="paquete" {{ old('unidad_medida') == 'paquete' ? 'selected' : '' }}>Paquetes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <small class="form-text text-muted mt-2" id="unidad-ejemplo">Ej: 1 kilogramo</small>
                                            </div>

                                            <div class="form-group">
                                                <label for="lote" class="font-weight-bold">
                                                    <i class="fas fa-layer-group text-danger mr-2"></i>Número de Lote 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-danger text-white">
                                                            <i class="fas fa-layer-group"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control" id="lote" name="lote" 
                                                           value="{{ old('lote') }}" required placeholder="Ej: LOTE-2024-001">
                                                </div>
                                                <div class="invalid-feedback">Por favor ingresa un número de lote válido.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información Adicional -->
                            <div class="row">
                                <div class="col-lg-6 col-md-12 mb-4">
                                    <div class="card shadow-sm border-0 modern-inner-card" style="border-left: 5px solid #ffc107 !important;">
                                        <div class="card-header bg-gradient-warning text-dark">
                                            <h4 class="card-title mb-0">
                                                <i class="fas fa-calendar-alt mr-2"></i>Información Adicional
                                            </h4>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="form-group">
                                                <label for="fecha_vencimiento" class="font-weight-bold">
                                                    <i class="fas fa-calendar-times text-danger mr-2"></i>Fecha de Vencimiento 
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-danger text-white">
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" 
                                                           value="{{ old('fecha_vencimiento') }}" required min="{{ date('Y-m-d') }}">
                                                </div>
                                                <div class="invalid-feedback">Por favor ingresa una fecha de vencimiento válida.</div>
                                            </div>

                                            <div class="form-group">
                                                <label for="stock" class="font-weight-bold">
                                                    <i class="fas fa-boxes text-primary mr-2"></i>Stock Inicial
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-primary text-white">
                                                            <i class="fas fa-boxes"></i>
                                                        </span>
                                                    </div>
                                                    <input type="number" class="form-control" id="stock" name="stock" 
                                                           value="{{ old('stock', '0') }}" min="0" placeholder="Cantidad disponible">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12 mb-4">
                                    <div class="card shadow-sm border-0 modern-inner-card">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-file-alt text-secondary mr-2"></i>Descripción
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="descripcion" class="font-weight-bold">
                                                    <i class="fas fa-comment-alt mr-2"></i>Descripción del Producto
                                                </label>
                                                <textarea class="form-control form-control-lg" id="descripcion" name="descripcion" 
                                                          rows="4" placeholder="Descripción detallada del producto...">{{ old('descripcion') }}</textarea>
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
                                <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-lg mr-2">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                                <button type="submit" form="productForm" class="btn btn-success btn-lg shadow" id="btnSubmit">
                                    <i class="fas fa-save mr-2"></i>Guardar Producto
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
    // Auto-generar código de barras si está vacío
    const codigoBarrasInput = document.getElementById('codigo_barras');
    const nombreInput = document.getElementById('nombre');
    
    nombreInput.addEventListener('blur', function() {
        if (!codigoBarrasInput.value) {
            const baseCode = nombreInput.value.replace(/\s+/g, '').substring(0, 8).toUpperCase();
            const randomNum = Math.floor(Math.random() * 9000) + 1000;
            codigoBarrasInput.value = baseCode + randomNum;
        }
    });

    // Actualizar ejemplo de unidad de medida
    const unidadSelect = document.getElementById('unidad_medida');
    const pesoInput = document.getElementById('peso');
    const unidadEjemplo = document.getElementById('unidad-ejemplo');

    function actualizarEjemplo() {
        const unidades = {
            'kg': 'kilogramo',
            'g': 'gramo', 
            'ton': 'tonelada',
            'lb': 'libra',
            'l': 'litro',
            'ml': 'mililitro',
            'unidad': 'unidad',
            'caja': 'caja',
            'paquete': 'paquete'
        };
        const valor = pesoInput.value || '1';
        const unidad = unidades[unidadSelect.value];
        unidadEjemplo.textContent = `Ej: ${valor} ${unidad}${valor !== '1' ? 's' : ''}`;
    }

    unidadSelect.addEventListener('change', actualizarEjemplo);
    pesoInput.addEventListener('input', actualizarEjemplo);
    actualizarEjemplo();

    // Manejar estados activos del selector de temperatura
    $('input[type="radio"][name="temperatura_requerida"]').on('change', function() {
        $('.modern-temp-btn').removeClass('active');
        $(this).next('label.modern-temp-btn').addClass('active');
    });

    // Inicializar estado activo al cargar
    $('input[type="radio"][name="temperatura_requerida"]:checked').each(function() {
        $(this).next('label.modern-temp-btn').addClass('active');
    });

    // Validación en tiempo real
    const form = document.getElementById('productForm');
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
    });

    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
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
#temp_caliente:checked ~ label.modern-temp-btn,
label.modern-temp-btn:has(#temp_caliente:checked) {
    border-color: #ffc107 !important;
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%) !important;
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4) !important;
}

#temp_caliente:checked ~ label.modern-temp-btn .temp-icon-wrapper,
label.modern-temp-btn:has(#temp_caliente:checked) .temp-icon-wrapper {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
}

#temp_caliente:checked ~ label.modern-temp-btn .temp-icon-wrapper i,
label.modern-temp-btn:has(#temp_caliente:checked) .temp-icon-wrapper i {
    color: white;
}

#temp_frio:checked ~ label.modern-temp-btn,
label.modern-temp-btn:has(#temp_frio:checked) {
    border-color: #17a2b8 !important;
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%) !important;
    box-shadow: 0 10px 25px rgba(23, 162, 184, 0.4) !important;
}

#temp_frio:checked ~ label.modern-temp-btn .temp-icon-wrapper,
label.modern-temp-btn:has(#temp_frio:checked) .temp-icon-wrapper {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

#temp_frio:checked ~ label.modern-temp-btn .temp-icon-wrapper i,
label.modern-temp-btn:has(#temp_frio:checked) .temp-icon-wrapper i {
    color: white;
}

#temp_congelado_prod:checked ~ label.modern-temp-btn,
label.modern-temp-btn:has(#temp_congelado_prod:checked) {
    border-color: #007bff !important;
    background: linear-gradient(135deg, #cce5ff 0%, #99ccff 100%) !important;
    box-shadow: 0 10px 25px rgba(0, 123, 255, 0.4) !important;
}

#temp_congelado_prod:checked ~ label.modern-temp-btn .temp-icon-wrapper,
label.modern-temp-btn:has(#temp_congelado_prod:checked) .temp-icon-wrapper {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

#temp_congelado_prod:checked ~ label.modern-temp-btn .temp-icon-wrapper i,
label.modern-temp-btn:has(#temp_congelado_prod:checked) .temp-icon-wrapper i {
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
