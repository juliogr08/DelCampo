@extends('tienda-panel.layouts.app')

@section('title', 'Nuevo Producto - ' . $tienda->nombre)
@section('page-title', 'Nuevo Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('tienda.panel.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="nombre">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3"
                                      class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="precio">Precio (Bs) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="precio" id="precio" 
                                           class="form-control @error('precio') is-invalid @enderror"
                                           value="{{ old('precio') }}" required min="0">
                                    @error('precio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock Inicial</label>
                                    <div class="alert alert-info mb-0 py-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <small>El stock se agrega cuando el administrador apruebe tu solicitud de reposición.</small>
                                    </div>
                                </div>
                            </div>
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
                                @error('imagen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <img id="preview" src="" alt="" class="img-fluid mt-3 d-none" style="max-height: 200px;">
                        </div>

                        <div class="form-group">
                            <div class="alert alert-warning py-2 mb-0">
                                <i class="fas fa-clock mr-1"></i>
                                <small><strong>Estado:</strong> El producto iniciará <span class="badge badge-secondary">Inactivo</span> y se activará automáticamente cuando recibas stock del administrador.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Guardar Producto
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
