@extends('layouts.app')

@section('title', 'Editar Producto - Proven')

@section('content-header', 'Editar Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
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
                            <i class="fas fa-edit mr-2"></i>Editar Producto: {{ $producto->nombre }}
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('productos.update', $producto->id) }}" method="POST" id="productForm" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            
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
                                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                                           id="nombre" name="nombre" 
                                                           value="{{ old('nombre', $producto->nombre) }}" 
                                                           required placeholder="Ej: Leche Entera 1L">
                                                </div>
                                                @error('nombre')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
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
                                                    <input type="text" class="form-control @error('codigo_barras') is-invalid @enderror" 
                                                           id="codigo_barras" name="codigo_barras" 
                                                           value="{{ old('codigo_barras', $producto->codigo_barras) }}" 
                                                           required placeholder="Ej: 7501234567890">
                                                </div>
                                                <small class="form-text text-muted mt-2">
                                                    <i class="fas fa-info-circle mr-1"></i>Código único para identificar el producto
                                                </small>
                                                @error('codigo_barras')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
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
                                                    <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" 
                                                           id="precio" name="precio" 
                                                           value="{{ old('precio', number_format(floatval($producto->precio ?? 0), 2, '.', '')) }}" 
                                                           required min="0" placeholder="0.00">
                                                </div>
                                                @error('precio')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
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
                                                    <input type="text" class="form-control @error('lote') is-invalid @enderror" 
                                                           id="lote" name="lote" 
                                                           value="{{ old('lote', $producto->lote) }}" 
                                                           required placeholder="Ej: LOTE-2024-001">
                                                </div>
                                                @error('lote')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="stock" class="font-weight-bold">
                                                    <i class="fas fa-boxes text-primary mr-2"></i>Stock Disponible
                                                    <span class="badge badge-danger">Requerido</span>
                                                </label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-primary text-white">
                                                            <i class="fas fa-boxes"></i>
                                                        </span>
                                                    </div>
                                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                                           id="stock" name="stock" 
                                                           value="{{ old('stock', $producto->stock) }}" 
                                                           required min="0" placeholder="Cantidad disponible">
                                                </div>
                                                @error('stock')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

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
                                                    <input type="date" class="form-control @error('fecha_vencimiento') is-invalid @enderror" 
                                                           id="fecha_vencimiento" name="fecha_vencimiento" 
                                                           value="{{ old('fecha_vencimiento', $producto->fecha_vencimiento ? \Carbon\Carbon::parse($producto->fecha_vencimiento)->format('Y-m-d') : '') }}" 
                                                           required>
                                                </div>
                                                @error('fecha_vencimiento')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="row">
                                <div class="col-12 mb-4">
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
                                                <textarea class="form-control form-control-lg @error('descripcion') is-invalid @enderror" 
                                                          id="descripcion" name="descripcion" 
                                                          rows="4" 
                                                          placeholder="Descripción detallada del producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                                                @error('descripcion')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
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
                                <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-info btn-lg mr-2">
                                    <i class="fas fa-eye mr-2"></i>Ver Detalles
                                </a>
                                <button type="submit" form="productForm" class="btn btn-success btn-lg shadow" id="btnSubmit">
                                    <i class="fas fa-save mr-2"></i>Actualizar Producto
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

.btn-info.btn-lg {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border: none;
}

.btn-info.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(23, 162, 184, 0.4);
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
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
@endpush
