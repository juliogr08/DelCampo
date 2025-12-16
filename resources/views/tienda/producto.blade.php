@extends('tienda.layouts.app')

@section('title', $producto->nombre)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('tienda.home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tienda.catalogo') }}">Productos</a></li>
            <li class="breadcrumb-item active">{{ $producto->nombre }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Imagen -->
        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-sm">
                @if($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                         class="card-img-top" alt="{{ $producto->nombre }}"
                         style="max-height: 400px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 400px;">
                        <i class="fas fa-image fa-5x text-muted"></i>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Información -->
        <div class="col-md-7">
            <span class="badge bg-secondary mb-2">{{ $producto->categoria_nombre }}</span>
            
            <h1 class="mb-3">{{ $producto->nombre }}</h1>
            
            <h2 class="text-primary mb-4">{{ number_format($producto->precio, 2) }} Bs</h2>
            
            @if($producto->descripcion)
                <p class="text-muted mb-4">{{ $producto->descripcion }}</p>
            @endif
            
            <div class="mb-4">
                @if($producto->stock > 0)
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-check me-1"></i>En Stock ({{ $producto->stock }} disponibles)
                    </span>
                @else
                    <span class="badge bg-danger fs-6">
                        <i class="fas fa-times me-1"></i>Sin Stock
                    </span>
                @endif
            </div>
            
            @if($producto->stock > 0)
                <form action="{{ route('tienda.carrito.agregar', $producto) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" name="cantidad" class="form-control" 
                                   value="1" min="1" max="{{ $producto->stock }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-cart-plus me-2"></i>Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </form>
            @endif
            
            <hr>
            
            <div class="row text-center">
                <div class="col-4">
                    <i class="fas fa-truck fa-2x text-primary mb-2"></i>
                    <p class="small mb-0">Delivery</p>
                </div>
                <div class="col-4">
                    <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                    <p class="small mb-0">Compra Segura</p>
                </div>
                <div class="col-4">
                    <i class="fas fa-headset fa-2x text-primary mb-2"></i>
                    <p class="small mb-0">Soporte</p>
                </div>
            </div>

            <!-- Tienda que vende este producto -->
            @if($producto->tienda)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="text-muted mb-3"><i class="fas fa-store me-2"></i>Vendido por</h6>
                    <a href="{{ route('tienda.perfil', $producto->tienda->slug) }}" class="text-decoration-none">
                        <div class="d-flex align-items-center">
                            @if($producto->tienda->logo_path)
                                <img src="{{ asset('storage/' . $producto->tienda->logo_path) }}" 
                                     alt="{{ $producto->tienda->nombre }}"
                                     class="rounded-circle shadow-sm me-3"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 shadow-sm"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-store fa-lg"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h5 class="mb-1 text-dark">{{ $producto->tienda->nombre }}</h5>
                                @if($producto->tienda->descripcion)
                                    <p class="text-muted small mb-0">{{ Str::limit($producto->tienda->descripcion, 60) }}</p>
                                @endif
                            </div>
                            <div>
                                <span class="btn btn-outline-primary btn-sm">
                                    Ver Tienda <i class="fas fa-chevron-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Productos relacionados -->
    @if($relacionados->count() > 0)
        <section class="mt-5">
            <h3 class="mb-4">Productos Relacionados</h3>
            <div class="row">
                @foreach($relacionados as $rel)
                    <div class="col-md-3 col-sm-6 mb-4">
                        @include('tienda.partials.product-card', ['producto' => $rel])
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
