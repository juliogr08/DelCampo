@extends('tienda.layouts.app')

@section('title', $tiendaPerfil->nombre)

@section('content')
<div class="container py-4">
    <!-- Store Header -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    @if($tiendaPerfil->logo_path)
                        <img src="{{ asset('storage/' . $tiendaPerfil->logo_path) }}" 
                             alt="{{ $tiendaPerfil->nombre }}"
                             class="rounded-circle shadow"
                             style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto shadow"
                             style="width: 120px; height: 120px;">
                            <i class="fas fa-store fa-3x"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-7">
                    <h1 class="mb-2">{{ $tiendaPerfil->nombre }}</h1>
                    @if($tiendaPerfil->descripcion)
                        <p class="text-muted mb-3">{{ $tiendaPerfil->descripcion }}</p>
                    @endif
                    <div class="d-flex flex-wrap gap-3">
                        <span class="text-muted">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                            {{ $tiendaPerfil->direccion }}
                        </span>
                        <span class="text-muted">
                            <i class="fas fa-phone text-success me-1"></i>
                            {{ $tiendaPerfil->telefono }}
                        </span>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h3 text-primary mb-0">{{ $productos->total() }}</div>
                            <small class="text-muted">Productos</small>
                        </div>
                        <div class="col-6">
                            <div class="h3 text-success mb-0">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <small class="text-muted">Verificada</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Map -->
    @if($tiendaPerfil->latitud && $tiendaPerfil->longitud)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt text-danger me-2"></i>Ubicación de la Tienda</h5>
        </div>
        <div class="card-body p-0">
            <div id="storeMap" style="height: 300px; width: 100%;"></div>
        </div>
    </div>
    @endif

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('tienda.home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tienda.catalogo') }}">Catálogo</a></li>
            <li class="breadcrumb-item active">{{ $tiendaPerfil->nombre }}</li>
        </ol>
    </nav>

    <!-- Products -->
    <h2 class="mb-4">Productos de {{ $tiendaPerfil->nombre }}</h2>
    
    @if($productos->count() > 0)
        <div class="row">
            @foreach($productos as $producto)
                <div class="col-md-3 col-sm-6 mb-4">
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
            <h4>Esta tienda aún no tiene productos</h4>
            <p class="text-muted">Vuelve pronto para ver sus novedades</p>
            <a href="{{ route('tienda.catalogo') }}" class="btn btn-primary">
                Ver todos los productos
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
@if($tiendaPerfil->latitud && $tiendaPerfil->longitud)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('storeMap').setView([{{ $tiendaPerfil->latitud }}, {{ $tiendaPerfil->longitud }}], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    var marker = L.marker([{{ $tiendaPerfil->latitud }}, {{ $tiendaPerfil->longitud }}]).addTo(map);
    marker.bindPopup('<b>{{ $tiendaPerfil->nombre }}</b><br>{{ $tiendaPerfil->direccion }}').openPopup();
});
</script>
@endif
@endpush
