@extends('tienda-panel.layouts.app')

@section('title', 'Proponer Producto - ' . $tienda->nombre)
@section('page-title', 'Proponer Nuevo Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Proponer Producto</li>
@endsection

@section('content')
    <div class="alert alert-info">
        <i class="fas fa-lightbulb mr-2"></i>
        <strong>¿Nuevo producto?</strong> Si no encuentras un producto en el catálogo del administrador, 
        puedes proponerlo aquí. Una vez aprobado, estará disponible para todas las tiendas.
    </div>

    <div class="card">
        <form action="{{ route('tienda.panel.proponer-producto.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="nombre">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre') }}" required 
                                   placeholder="Ej: Manzana Roja Fuji">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3"
                                      class="form-control @error('descripcion') is-invalid @enderror"
                                      placeholder="Describe el producto...">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria">Categoría <span class="text-danger">*</span></label>
                                    <select name="categoria" id="categoria" class="form-control @error('categoria') is-invalid @enderror" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($categorias as $key => $nombre)
                                            <option value="{{ $key }}" {{ old('categoria') == $key ? 'selected' : '' }}>
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
                                        <option value="">Seleccionar...</option>
                                        @foreach($unidades as $key => $nombre)
                                            <option value="{{ $key }}" {{ old('unidad_medida') == $key ? 'selected' : '' }}>
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

                        <div class="form-group">
                            <label for="precio_sugerido">Precio Sugerido (Bs) <small class="text-muted">(opcional)</small></label>
                            <input type="number" step="0.01" name="precio_sugerido" id="precio_sugerido" 
                                   class="form-control @error('precio_sugerido') is-invalid @enderror"
                                   value="{{ old('precio_sugerido') }}" min="0"
                                   placeholder="Sugiere un precio mayor">
                            <small class="text-muted">El administrador definirá el precio final.</small>
                            @error('precio_sugerido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Imagen del Producto</label>
                            <div class="custom-file">
                                <input type="file" name="imagen" id="imagen" 
                                       class="custom-file-input @error('imagen') is-invalid @enderror"
                                       accept="image/*" onchange="previewImage(this)">
                                <label class="custom-file-label" for="imagen">Seleccionar imagen</label>
                            </div>
                            <img id="preview" src="" alt="" class="img-fluid mt-3 d-none" style="max-height: 200px;">
                        </div>

                        <div class="alert alert-warning py-2">
                            <i class="fas fa-clock mr-1"></i>
                            <small><strong>Nota:</strong> El producto será revisado por el administrador antes de ser aprobado.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-1"></i> Enviar Propuesta
                </button>
                <a href="{{ route('tienda.panel.mis-propuestas') }}" class="btn btn-secondary">
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
