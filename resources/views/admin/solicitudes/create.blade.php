@extends('admin.layouts.app')

@section('title', 'Nueva Solicitud - Admin')
@section('page-title', 'Nueva Solicitud de Reposición')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.solicitudes.index') }}">Solicitudes</a></li>
    <li class="breadcrumb-item active">Nueva</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('admin.solicitudes.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-plus-circle mr-2"></i>Datos de la Solicitud</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="almacen_id">Almacén de Destino <span class="text-danger">*</span></label>
                            <select name="almacen_id" id="almacen_id" class="form-control @error('almacen_id') is-invalid @enderror" required>
                                <option value="">Seleccionar almacén...</option>
                                @foreach($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}" {{ old('almacen_id') == $almacen->id ? 'selected' : '' }}>
                                        {{ $almacen->nombre_almacen }} - {{ $almacen->ubicacion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('almacen_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="producto_id">Producto <span class="text-danger">*</span></label>
                            <select name="producto_id" id="producto_id" class="form-control @error('producto_id') is-invalid @enderror" required>
                                <option value="">Seleccionar producto...</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                        {{ $producto->nombre }} (Stock: {{ $producto->stock }}, Mínimo: {{ $producto->stock_minimo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('producto_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cantidad_solicitada">Cantidad a Solicitar <span class="text-danger">*</span></label>
                            <input type="number" name="cantidad_solicitada" id="cantidad_solicitada" 
                                   class="form-control @error('cantidad_solicitada') is-invalid @enderror"
                                   value="{{ old('cantidad_solicitada', 10) }}" min="1" required>
                            @error('cantidad_solicitada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notas">Notas (opcional)</label>
                            <textarea name="notas" id="notas" rows="3" class="form-control"
                                      placeholder="Observaciones o instrucciones especiales...">{{ old('notas') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-1"></i> Crear Solicitud
                        </button>
                        <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            @if($stockBajo->count() > 0)
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-2"></i>Productos con Stock Bajo</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($stockBajo as $producto)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $producto->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">Mínimo: {{ $producto->stock_minimo }}</small>
                                    </div>
                                    <span class="badge badge-danger badge-pill">{{ $producto->stock }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
