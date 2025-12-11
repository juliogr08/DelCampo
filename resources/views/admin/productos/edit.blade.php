@extends('admin.layouts.app')

@section('title', 'Editar Producto - Admin')
@section('page-title', 'Editar Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                                           value="{{ old('nombre', $producto->nombre) }}" required>
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
                                           value="{{ old('codigo_barras', $producto->codigo_barras) }}" required>
                                    @error('codigo_barras')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3" 
                                      class="form-control">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="precio">Precio (Bs) *</label>
                                    <input type="number" name="precio" id="precio" step="0.01" min="0"
                                           class="form-control @error('precio') is-invalid @enderror"
                                           value="{{ old('precio', $producto->precio) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock">Stock *</label>
                                    <input type="number" name="stock" id="stock" min="0"
                                           class="form-control @error('stock') is-invalid @enderror"
                                           value="{{ old('stock', $producto->stock) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stock_minimo">Stock Mínimo *</label>
                                    <input type="number" name="stock_minimo" id="stock_minimo" min="0"
                                           class="form-control"
                                           value="{{ old('stock_minimo', $producto->stock_minimo) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria">Categoría *</label>
                                    <select name="categoria" id="categoria" class="form-control" required>
                                        @foreach(App\Models\Producto::CATEGORIAS as $key => $value)
                                            <option value="{{ $key }}" {{ old('categoria', $producto->categoria) == $key ? 'selected' : '' }}>
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
                                            <option value="{{ $key }}" {{ old('unidad_medida', $producto->unidad_medida) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                            <label>Imagen Actual</label>
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="" 
                                     style="max-width: 100%; border-radius: 8px; margin-bottom: 10px;">
                            @else
                                <p class="text-muted">Sin imagen</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="imagen">Cambiar Imagen</label>
                            <input type="file" name="imagen" id="imagen" class="form-control-file" accept="image/*">
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="activo" value="1" 
                                       class="custom-control-input" id="activo" 
                                       {{ $producto->activo ? 'checked' : '' }}>
                                <label class="custom-control-label" for="activo">Producto Activo</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="destacado" value="1" 
                                       class="custom-control-input" id="destacado"
                                       {{ $producto->destacado ? 'checked' : '' }}>
                                <label class="custom-control-label" for="destacado">Producto Destacado</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Actualizar
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
