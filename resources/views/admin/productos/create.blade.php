@extends('admin.layouts.app')

@section('title', 'Crear Producto - Admin')
@section('page-title', 'Crear Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Crear</li>
@endsection

@section('content')
    <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información del Producto</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nombre">Nombre *</label>
                                    <input type="text" name="nombre" id="nombre" 
                                           class="form-control @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre') }}" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="codigo_barras">Código *</label>
                                    <input type="text" name="codigo_barras" id="codigo_barras" 
                                           class="form-control @error('codigo_barras') is-invalid @enderror"
                                           value="{{ old('codigo_barras') }}" required>
                                    @error('codigo_barras')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3" 
                                      class="form-control">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="precio">Precio (Bs) *</label>
                                    <input type="number" name="precio" id="precio" step="0.01" min="0"
                                           class="form-control @error('precio') is-invalid @enderror"
                                           value="{{ old('precio') }}" required>
                                    @error('precio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock">Stock *</label>
                                    <input type="number" name="stock" id="stock" min="0"
                                           class="form-control @error('stock') is-invalid @enderror"
                                           value="{{ old('stock', 0) }}" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock_minimo">Stock Mínimo *</label>
                                    <input type="number" name="stock_minimo" id="stock_minimo" min="0"
                                           class="form-control @error('stock_minimo') is-invalid @enderror"
                                           value="{{ old('stock_minimo', 5) }}" required>
                                    @error('stock_minimo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria">Categoría *</label>
                                    <select name="categoria" id="categoria" class="form-control" required>
                                        @foreach(App\Models\Producto::CATEGORIAS as $key => $value)
                                            <option value="{{ $key }}" {{ old('categoria') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unidad_medida">Unidad de Medida *</label>
                                    <select name="unidad_medida" id="unidad_medida" class="form-control" required>
                                        @foreach(App\Models\Producto::UNIDADES_MEDIDA as $key => $value)
                                            <option value="{{ $key }}" {{ old('unidad_medida', 'unidad') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="peso">Peso (opcional)</label>
                            <input type="number" name="peso" id="peso" step="0.001" min="0"
                                   class="form-control" value="{{ old('peso') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Imagen y Estado</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="imagen">Imagen del Producto</label>
                            <input type="file" name="imagen" id="imagen" class="form-control-file"
                                   accept="image/*" onchange="previewImage(event)">
                            <img id="preview" src="" alt="" 
                                 style="max-width: 100%; margin-top: 10px; display: none; border-radius: 8px;">
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="activo" value="1" 
                                       class="custom-control-input" id="activo" checked>
                                <label class="custom-control-label" for="activo">Producto Activo</label>
                            </div>
                            <small class="text-muted">Los productos activos se muestran en la tienda</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="destacado" value="1" 
                                       class="custom-control-input" id="destacado">
                                <label class="custom-control-label" for="destacado">Producto Destacado</label>
                            </div>
                            <small class="text-muted">Se mostrará en la página principal</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Guardar Producto
                        </button>
                        <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary btn-block">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = 'block';
}
</script>
@endpush
