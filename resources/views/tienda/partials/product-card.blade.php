<div class="card product-card h-100">
    @if($producto->imagen)
        <img src="{{ asset('storage/' . $producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}">
    @else
        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
            <i class="fas fa-image fa-3x text-muted"></i>
        </div>
    @endif
    
    <div class="card-body d-flex flex-column">
        {{-- Store info badge --}}
        @if($producto->tienda)
            <a href="{{ route('tienda.perfil', $producto->tienda->slug) }}" 
               class="d-flex align-items-center text-decoration-none mb-2 store-badge">
                @if($producto->tienda->logo_path)
                    <img src="{{ asset('storage/' . $producto->tienda->logo_path) }}" 
                         class="rounded-circle me-2" 
                         style="width: 24px; height: 24px; object-fit: cover;"
                         alt="{{ $producto->tienda->nombre }}">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                         style="width: 24px; height: 24px; font-size: 12px;">
                        <i class="fas fa-store"></i>
                    </div>
                @endif
                <small class="text-muted">{{ Str::limit($producto->tienda->nombre, 20) }}</small>
            </a>
        @endif
        
        <span class="badge bg-secondary mb-2" style="width: fit-content;">{{ $producto->categoria_nombre }}</span>
        
        <h5 class="card-title">{{ $producto->nombre }}</h5>
        
        <p class="product-price mt-auto mb-2">{{ number_format($producto->precio, 2) }} Bs</p>
        
        <div class="d-flex gap-2">
            <a href="{{ route('tienda.producto', $producto) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                Ver
            </a>
            <form action="{{ route('tienda.carrito.agregar', $producto) }}" method="POST" class="flex-grow-1">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-cart-plus"></i>
                </button>
            </form>
        </div>
    </div>
    
    @if($producto->destacado)
        <span class="position-absolute top-0 end-0 m-2 badge bg-warning">
            <i class="fas fa-star"></i> Destacado
        </span>
    @endif
</div>

<style>
.store-badge:hover small {
    color: var(--bs-primary) !important;
    text-decoration: underline;
}
</style>

