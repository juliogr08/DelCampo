@extends('tienda.layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Filtros -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tienda.catalogo') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Buscar</label>
                            <input type="text" name="buscar" class="form-control" 
                                   placeholder="Nombre del producto..." 
                                   value="{{ request('buscar') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select name="categoria" class="form-select">
                                <option value="">Todas</option>
                                @foreach($categorias as $key => $nombre)
                                    <option value="{{ $key }}" {{ request('categoria') == $key ? 'selected' : '' }}>
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        @if(isset($tiendas) && $tiendas->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Tienda</label>
                                <select name="tienda" class="form-select">
                                    <option value="">Todas las tiendas</option>
                                    @foreach($tiendas as $t)
                                        <option value="{{ $t->id }}" {{ request('tienda') == $t->id ? 'selected' : '' }}>
                                            {{ $t->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label">Ordenar por</label>
                            <select name="orden" class="form-select">
                                <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
                                <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                                <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                                <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre A-Z</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Aplicar Filtros
                        </button>
                        @if(request()->hasAny(['buscar', 'categoria', 'orden', 'tienda']))
                            <a href="{{ route('tienda.catalogo') }}" class="btn btn-outline-secondary w-100 mt-2">
                                Limpiar filtros
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Productos -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    @if(request('categoria'))
                        {{ $categorias[request('categoria')] ?? 'Productos' }}
                    @else
                        Todos los Productos
                    @endif
                </h2>
                <span class="text-muted">{{ $productos->total() }} productos</span>
            </div>
            
            @if($productos->count() > 0)
                <div class="row">
                    @foreach($productos as $producto)
                        <div class="col-md-4 col-sm-6 mb-4">
                            @include('tienda.partials.product-card', ['producto' => $producto])
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $productos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h4>No se encontraron productos</h4>
                    <p class="text-muted">Intenta con otros filtros de búsqueda</p>
                    <a href="{{ route('tienda.catalogo') }}" class="btn btn-primary">
                        Ver todos los productos
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
