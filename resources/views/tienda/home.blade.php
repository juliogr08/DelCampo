@extends('tienda.layouts.app')

@section('title', 'Del Campo - Productos Frescos del Campo')

@section('content')
    <!-- Hero Section -->
    <section class="hero text-center position-relative">
        <div class="container position-relative" style="z-index: 2;">
            <h1 class="display-4 fw-bold mb-3">Del Campo a Tu Mesa</h1>
            <p class="lead mb-4 fs-5">Productos frescos cultivados en el campo boliviano.<br>Papas, choclo, verduras y más, directos a tu hogar.</p>
            <a href="{{ route('tienda.catalogo') }}" class="btn btn-light btn-lg px-5">
                <i class="fas fa-leaf me-2"></i>Ver Productos
            </a>
        </div>
    </section>

    <div class="container">
        <!-- Categorías -->
        <section class="mb-5">
            <h3 class="text-center mb-4" style="color: var(--verde-bosque); font-weight: 700;">
                <i class="fas fa-tags me-2"></i>Nuestras Categorías
            </h3>
            <div class="text-center">
                @foreach($categorias as $key => $nombre)
                    <a href="{{ route('tienda.catalogo', ['categoria' => $key]) }}" class="category-badge">
                        @switch($key)
                            @case('tuberculos')
                                <i class="fas fa-carrot me-1"></i>
                                @break
                            @case('verduras')
                                <i class="fas fa-leaf me-1"></i>
                                @break
                            @case('frutas')
                                <i class="fas fa-apple-alt me-1"></i>
                                @break
                            @case('granos')
                                <i class="fas fa-seedling me-1"></i>
                                @break
                            @case('lacteos')
                                <i class="fas fa-cheese me-1"></i>
                                @break
                            @default
                                <i class="fas fa-box me-1"></i>
                        @endswitch
                        {{ $nombre }}
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Productos Destacados -->
        @if($productosDestacados->count() > 0)
            <section class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 style="color: var(--verde-bosque); font-weight: 700;">
                        <i class="fas fa-star text-warning me-2"></i>Productos Destacados
                    </h3>
                    <a href="{{ route('tienda.catalogo') }}" class="btn btn-outline-primary">Ver todos</a>
                </div>
                <div class="row">
                    @foreach($productosDestacados as $producto)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            @include('tienda.partials.product-card', ['producto' => $producto])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Productos Recientes -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 style="color: var(--verde-bosque); font-weight: 700;">
                    <i class="fas fa-clock me-2"></i>Recién Cosechados
                </h3>
                <a href="{{ route('tienda.catalogo') }}" class="btn btn-outline-primary">Ver todos</a>
            </div>
            <div class="row">
                @foreach($productosRecientes as $producto)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        @include('tienda.partials.product-card', ['producto' => $producto])
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Info Cards -->
        <section class="mb-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="info-card h-100">
                        <i class="fas fa-truck fa-3x mb-3"></i>
                        <h5 style="color: var(--verde-bosque); font-weight: 700;">Delivery a Domicilio</h5>
                        <p class="text-muted mb-0">Entrega rápida en todo Santa Cruz de la Sierra. Del campo a tu puerta.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="info-card h-100">
                        <i class="fas fa-seedling fa-3x mb-3"></i>
                        <h5 style="color: var(--verde-bosque); font-weight: 700;">100% Natural</h5>
                        <p class="text-muted mb-0">Productos frescos cultivados de manera tradicional, sin químicos.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="info-card h-100">
                        <i class="fas fa-heart fa-3x mb-3"></i>
                        <h5 style="color: var(--verde-bosque); font-weight: 700;">Apoyo Local</h5>
                        <p class="text-muted mb-0">Trabajamos directamente con agricultores bolivianos.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
