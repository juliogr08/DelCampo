@extends('tienda-panel.layouts.app')

@section('title', 'Editar Producto - ' . $tienda->nombre)
@section('page-title', 'Editar Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('tienda.panel.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="nombre">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $producto->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3"
                                      class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria">Categoría <span class="text-danger">*</span></label>
                                    <select name="categoria" id="categoria" class="form-control @error('categoria') is-invalid @enderror" required>
                                        @foreach($categorias as $key => $nombre)
                                            <option value="{{ $key }}" {{ old('categoria', $producto->categoria) == $key ? 'selected' : '' }}>
                                                {{ $nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unidad_medida">Unidad de Medida <span class="text-danger">*</span></label>
                                    <select name="unidad_medida" id="unidad_medida" class="form-control @error('unidad_medida') is-invalid @enderror" required>
                                        @foreach($unidades as $key => $nombre)
                                            <option value="{{ $key }}" {{ old('unidad_medida', $producto->unidad_medida) == $key ? 'selected' : '' }}>
                                                {{ $nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unidad_medida')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="precio">Precio (Bs) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="precio" id="precio" 
                                           class="form-control @error('precio') is-invalid @enderror"
                                           value="{{ old('precio', $producto->precio) }}" required min="0">
                                    @error('precio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock">Stock <span class="text-danger">*</span></label>
                                    <input type="number" name="stock" id="stock" 
                                           class="form-control @error('stock') is-invalid @enderror"
                                           value="{{ old('stock', $producto->stock) }}" required min="0">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Imagen Actual</label>
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                     id="preview"
                                     class="img-fluid mb-3" style="max-height: 200px;">
                            @else
                                <div class="bg-light text-center py-4 mb-3">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                    <p class="text-muted mb-0">Sin imagen</p>
                                </div>
                                <img id="preview" src="" alt="" class="img-fluid mb-3 d-none" style="max-height: 200px;">
                            @endif
                            <div class="custom-file">
                                <input type="file" name="imagen" id="imagen" 
                                       class="custom-file-input @error('imagen') is-invalid @enderror"
                                       accept="image/*" onchange="previewImage(this)">
                                <label class="custom-file-label" for="imagen">Cambiar imagen</label>
                                @error('imagen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="activo" value="1" 
                                       class="custom-control-input" id="activo" 
                                       {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="activo">Producto activo</label>
                            </div>
                            <small class="text-muted">Los productos inactivos no aparecen en la tienda</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Guardar Cambios
                </button>
                <a href="{{ route('tienda.panel.productos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const label = input.nextElementSibling;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
        label.textContent = input.files[0].name;
    }
}
</script>
@endpush
