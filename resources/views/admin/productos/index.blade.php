@extends('admin.layouts.app')

@section('title', 'Productos - Admin')
@section('page-title', 'Productos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Productos</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Productos</h3>
            <div class="card-tools">
                <a href="{{ route('admin.productos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Producto
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filtros -->
            <form action="{{ route('admin.productos.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="buscar" class="form-control" 
                               placeholder="Buscar por nombre o código..." 
                               value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="categoria" class="form-control">
                            <option value="">Todas las categorías</option>
                            @foreach(App\Models\Producto::CATEGORIAS as $key => $value)
                                <option value="{{ $key }}" {{ request('categoria') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="estado" class="form-control">
                            <option value="">Todos</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Código</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td>
                                    @if($producto->imagen)
                                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                             alt="{{ $producto->nombre }}" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div style="width: 50px; height: 50px; background: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $producto->nombre }}</strong>
                                    @if($producto->destacado)
                                        <span class="badge badge-warning ml-1">
                                            <i class="fas fa-star"></i>
                                        </span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $producto->categoria_nombre }}</small>
                                </td>
                                <td><code>{{ $producto->codigo_barras }}</code></td>
                                <td><strong>{{ number_format($producto->precio, 2) }} Bs</strong></td>
                                <td>
                                    @if($producto->necesita_reposicion)
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
                                    <a href="{{ route('admin.productos.edit', $producto) }}" 
                                       class="btn btn-sm btn-info" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.productos.destroy', $producto) }}" 
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
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No hay productos registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
@endsection
