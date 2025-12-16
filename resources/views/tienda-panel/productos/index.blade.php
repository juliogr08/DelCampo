@extends('tienda-panel.layouts.app')

@section('title', 'Mis Productos - ' . $tienda->nombre)
@section('page-title', 'Mis Productos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Productos</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Productos</h3>
            <div class="card-tools">
                <a href="{{ route('tienda.panel.catalogo-admin') }}" class="btn btn-outline-primary btn-sm mr-2">
                    <i class="fas fa-store-alt mr-1"></i> Catálogo Admin
                </a>
                <a href="{{ route('tienda.panel.productos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nuevo Producto
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            @if($productos->count() > 0)
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th style="width: 80px">Imagen</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th style="width: 180px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>
                                    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" 
                                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td>{{ Str::limit($producto->nombre, 35) }}</td>
                                <td>{{ $producto->categoria_nombre }}</td>
                                <td>{{ number_format($producto->precio, 2) }} Bs</td>
                                <td>
                                    @if($producto->stock <= ($tienda->limite_stock_bajo ?? 5))
                                        <span class="badge badge-danger">{{ $producto->stock }}</span>
                                    @else
                                        <span class="badge badge-success">{{ $producto->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($producto->activo)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$producto->activo && $producto->stock == 0)
                                        <a href="{{ route('tienda.panel.solicitudes.create', ['producto_id' => $producto->id]) }}" 
                                           class="btn btn-sm btn-warning" title="Solicitar Stock">
                                            <i class="fas fa-truck-loading"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('tienda.panel.productos.edit', $producto) }}" 
                                       class="btn btn-sm btn-info" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tienda.panel.productos.destroy', $producto) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('¿Eliminar este producto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No tienes productos registrados</p>
                    <a href="{{ route('tienda.panel.catalogo-admin') }}" class="btn btn-primary mr-2">
                        <i class="fas fa-store-alt mr-1"></i> Ver Catálogo Admin
                    </a>
                    <a href="{{ route('tienda.panel.productos.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus mr-1"></i> Crear producto propio
                    </a>
                </div>
            @endif
        </div>
        @if($productos->hasPages())
            <div class="card-footer">
                {{ $productos->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Producto Adoptado - Solicitar Stock -->
    @if(session('producto_adoptado_id'))
        @php
            $productoAdoptado = \App\Models\Producto::find(session('producto_adoptado_id'));
        @endphp
        @if($productoAdoptado)
            <div class="modal fade" id="modalSolicitarStock" tabindex="-1" role="dialog" data-backdrop="static">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-check-circle mr-2"></i>¡Producto agregado!
                            </h5>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                @if($productoAdoptado->imagen)
                                    <img src="{{ $productoAdoptado->imagen_url }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 120px;">
                                @else
                                    <i class="fas fa-box fa-4x text-muted"></i>
                                @endif
                            </div>
                            
                            <h5 class="text-primary">{{ $productoAdoptado->nombre }}</h5>
                            <p class="text-muted mb-3">Tu precio: <strong>{{ number_format($productoAdoptado->precio, 2) }} Bs</strong></p>
                            
                            <div class="alert alert-warning py-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Tu producto está <strong>inactivo</strong> porque no tiene stock.<br>
                                <small>Solicita stock al administrador para comenzar a vender.</small>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <a href="{{ route('tienda.panel.solicitudes.create', ['producto_id' => $productoAdoptado->id]) }}" 
                               class="btn btn-success btn-lg">
                                <i class="fas fa-truck-loading mr-1"></i> Solicitar Stock Ahora
                            </a>
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                Más Tarde
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endsection

@if(session('producto_adoptado_id'))
@push('scripts')
<script>
$(document).ready(function() {
    $('#modalSolicitarStock').modal('show');
});
</script>
@endpush
@endif

