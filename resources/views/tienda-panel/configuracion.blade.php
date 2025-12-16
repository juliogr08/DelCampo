@extends('tienda-panel.layouts.app')

@section('title', 'Configuración - ' . $tienda->nombre)
@section('page-title', 'Configuración de la Tienda')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Configuración</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <form action="{{ route('tienda.panel.configuracion.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-store mr-2"></i>Datos de la Tienda</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nombre">Nombre de la Tienda <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" 
                                           class="form-control @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre', $tienda->nombre) }}" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea name="descripcion" id="descripcion" rows="3"
                                              class="form-control @error('descripcion') is-invalid @enderror"
                                              maxlength="280">{{ old('descripcion', $tienda->descripcion) }}</textarea>
                                    <small class="text-muted"><span id="charCount">{{ strlen($tienda->descripcion ?? '') }}</span>/280</small>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="telefono">Teléfono <span class="text-danger">*</span></label>
                                    <input type="tel" name="telefono" id="telefono" 
                                           class="form-control @error('telefono') is-invalid @enderror"
                                           value="{{ old('telefono', $tienda->telefono) }}" required>
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="direccion">Dirección <span class="text-danger">*</span></label>
                                    <input type="text" name="direccion" id="direccion" 
                                           class="form-control @error('direccion') is-invalid @enderror"
                                           value="{{ old('direccion', $tienda->direccion) }}" required>
                                    @error('direccion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group text-center">
                                    <label>Logo de la Tienda</label>
                                    <div class="mb-3">
                                        @if($tienda->logo_path)
                                            <img src="{{ asset('storage/' . $tienda->logo_path) }}" 
                                                 id="logoPreview"
                                                 class="img-fluid rounded-circle" 
                                                 style="width: 120px; height: 120px; object-fit: cover;">
                                        @else
                                            <div id="logoPlaceholder" class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                                 style="width: 120px; height: 120px;">
                                                <i class="fas fa-store fa-3x text-muted"></i>
                                            </div>
                                            <img id="logoPreview" class="img-fluid rounded-circle d-none" 
                                                 style="width: 120px; height: 120px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="logo" id="logo" 
                                               class="custom-file-input" accept="image/*" onchange="previewLogo(this)">
                                        <label class="custom-file-label" for="logo">Cambiar logo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-cog mr-2"></i>Configuración de Inventario</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="limite_stock_bajo">Límite de Stock Bajo</label>
                            <select name="limite_stock_bajo" id="limite_stock_bajo" class="form-control" style="max-width: 200px;">
                                @foreach($limitesStock as $limite)
                                    <option value="{{ $limite }}" {{ $tienda->limite_stock_bajo == $limite ? 'selected' : '' }}>
                                        {{ $limite }} unidades
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Recibirás alertas cuando el stock de un producto sea menor o igual a este límite.</small>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Estado de la Tienda</h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($tienda->estado === 'pendiente')
                            <span class="badge badge-warning p-3" style="font-size: 1rem;">
                                <i class="fas fa-clock mr-1"></i> Pendiente de Aprobación
                            </span>
                            <p class="text-muted mt-3">Realiza tu primera solicitud de productos para activar tu tienda.</p>
                        @elseif($tienda->estado === 'activa')
                            <span class="badge badge-success p-3" style="font-size: 1rem;">
                                <i class="fas fa-check-circle mr-1"></i> Tienda Activa
                            </span>
                            <p class="text-muted mt-3">Tu tienda está publicada y visible para los clientes.</p>
                        @else
                            <span class="badge badge-danger p-3" style="font-size: 1rem;">
                                <i class="fas fa-ban mr-1"></i> Tienda Suspendida
                            </span>
                            <p class="text-muted mt-3">Contacta al administrador para más información.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Estadísticas</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Productos</span>
                            <strong>{{ $tienda->productos()->count() }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Almacenes</span>
                            <strong>{{ $tienda->almacenes()->count() }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Solicitudes</span>
                            <strong>{{ $tienda->solicitudesReposicion()->count() }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('descripcion').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logoPreview');
            const placeholder = document.getElementById('logoPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(input.files[0]);
        input.nextElementSibling.textContent = input.files[0].name;
    }
}
</script>
@endpush
