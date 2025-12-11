@extends('layouts.app')

@section('title', 'Gestión de Productos')

@section('content-header', 'Gestión de Productos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Productos</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-box me-2"></i>Lista de Productos
        </h3>
        <div class="card-tools">
            <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Nuevo Producto
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($productos->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Precio</th>
                        <th>Lote</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr>
                        <td><strong>#{{ $producto->id }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cube text-success me-2"></i>
                                <div>
                                    <strong>{{ $producto->nombre }}</strong>
                                    @if($producto->descripcion)
                                    <br><small class="text-muted">{{ Str::limit($producto->descripcion, 40) }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $producto->codigo_barras }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-success">${{ number_format($producto->precio, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge nature-badge">{{ $producto->lote }}</span>
                        </td>
                        <td>
                            @if($producto->stock > 20)
                            <span class="badge bg-success">{{ $producto->stock }} unidades</span>
                            @elseif($producto->stock > 5)
                            <span class="badge bg-warning text-dark">{{ $producto->stock }} unidades</span>
                            @else
                            <span class="badge bg-danger">{{ $producto->stock }} unidades</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('productos.show', $producto->id) }}" 
                                   class="btn btn-info" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('productos.edit', $producto->id) }}" 
                                   class="btn btn-warning" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-danger delete-btn" 
                                        title="Eliminar"
                                        data-form="delete-form-{{ $producto->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $producto->id }}" 
                                      action="{{ route('productos.destroy', $producto->id) }}" 
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- CONTADOR EN LUGAR DE PAGINACIÓN --}}
        <div class="mt-3 text-muted text-center">
            <small>Total de productos: {{ $productos->count() }}</small>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No hay productos registrados</h5>
            <p class="text-muted">Comienza agregando tu primer producto al sistema</p>
            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>Crear Primer Producto
            </a>
        </div>
        @endif
    </div>
    {{-- ELIMINAMOS EL FOOTER CON PAGINACIÓN --}}
</div>

<!-- Modal de eliminación -->
@include('components.delete-modal')
@endsection