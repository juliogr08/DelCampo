@extends('admin.layouts.app')

@section('title', 'Editar Almacén - Admin')
@section('page-title', 'Editar Almacén')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.almacenes.index') }}">Almacenes</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <form action="{{ route('admin.almacenes.update', $almacen) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Editar: {{ $almacen->nombre_almacen }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre_almacen">Nombre del Almacén <span class="text-danger">*</span></label>
                            <input type="text" name="nombre_almacen" id="nombre_almacen" 
                                   class="form-control @error('nombre_almacen') is-invalid @enderror"
                                   value="{{ old('nombre_almacen', $almacen->nombre_almacen) }}" required>
                            @error('nombre_almacen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ubicacion">Dirección / Ubicación <span class="text-danger">*</span></label>
                            <textarea name="ubicacion" id="ubicacion" rows="2"
                                      class="form-control @error('ubicacion') is-invalid @enderror" required>{{ old('ubicacion', $almacen->ubicacion) }}</textarea>
                            @error('ubicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitud">Latitud <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="latitud" id="latitud" 
                                           class="form-control @error('latitud') is-invalid @enderror"
                                           value="{{ old('latitud', $almacen->latitud) }}" required>
                                    @error('latitud')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitud">Longitud <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="longitud" id="longitud" 
                                           class="form-control @error('longitud') is-invalid @enderror"
                                           value="{{ old('longitud', $almacen->longitud) }}" required>
                                    @error('longitud')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="responsable">Responsable</label>
                                    <input type="text" name="responsable" id="responsable" 
                                           class="form-control"
                                           value="{{ old('responsable', $almacen->responsable) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono_contacto">Teléfono de Contacto</label>
                                    <input type="text" name="telefono_contacto" id="telefono_contacto" 
                                           class="form-control"
                                           value="{{ old('telefono_contacto', $almacen->telefono_contacto) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-cog mr-2"></i>Configuración</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="activo" value="1" 
                                       class="custom-control-input" id="activo"
                                       {{ $almacen->activo ? 'checked' : '' }}>
                                <label class="custom-control-label" for="activo">
                                    <strong>Almacén Activo</strong>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="es_principal" value="1" 
                                       class="custom-control-input" id="es_principal"
                                       {{ $almacen->es_principal ? 'checked' : '' }}>
                                <label class="custom-control-label" for="es_principal">
                                    <strong>Almacén Principal</strong>
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="tipo_almacenamiento">Tipo de Almacenamiento</label>
                            <select name="tipo_almacenamiento" id="tipo_almacenamiento" class="form-control">
                                <option value="">Seleccionar...</option>
                                @foreach(App\Models\Almacen::TIPOS_ALMACENAMIENTO as $key => $value)
                                    <option value="{{ $key }}" {{ old('tipo_almacenamiento', $almacen->tipo_almacenamiento) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Actualizar
                        </button>
                        <a href="{{ route('admin.almacenes.index') }}" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
