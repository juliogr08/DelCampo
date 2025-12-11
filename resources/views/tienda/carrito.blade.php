@extends('tienda.layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Carrito de Compras</h2>
    
    @if(count($productos) > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        @foreach($productos as $item)
                            <div class="row align-items-center py-3 border-bottom">
                                <div class="col-md-2">
                                    @if($item['producto']->imagen)
                                        <img src="{{ asset('storage/' . $item['producto']->imagen) }}" 
                                             class="img-fluid rounded" alt="">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="height: 80px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ $item['producto']->nombre }}</h6>
                                    <small class="text-muted">{{ $item['producto']->categoria_nombre }}</small>
                                </div>
                                <div class="col-md-2">
                                    <form action="{{ route('tienda.carrito.actualizar', $item['producto']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="cantidad" class="form-control form-control-sm" 
                                               value="{{ $item['cantidad'] }}" min="1" 
                                               max="{{ $item['producto']->stock }}"
                                               onchange="this.form.submit()">
                                    </form>
                                </div>
                                <div class="col-md-2 text-end">
                                    <strong>{{ number_format($item['subtotal'], 2) }} Bs</strong>
                                </div>
                                <div class="col-md-2 text-end">
                                    <form action="{{ route('tienda.carrito.eliminar', $item['producto']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-white">
                        <form action="{{ route('tienda.carrito.vaciar') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-trash-alt me-1"></i>Vaciar Carrito
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Resumen</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <strong>{{ number_format($total, 2) }} Bs</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Envío</span>
                            <span>Se calcula al checkout</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total Estimado</strong>
                            <strong class="text-primary fs-5">{{ number_format($total, 2) }} Bs</strong>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        @auth
                            <a href="{{ route('tienda.checkout') }}" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-lock me-2"></i>Ir al Checkout
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-user me-2"></i>Iniciar Sesión para Continuar
                            </a>
                            <p class="text-center text-muted mt-2 mb-0">
                                <small>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></small>
                            </p>
                        @endauth
                    </div>
                </div>
                
                <a href="{{ route('tienda.catalogo') }}" class="btn btn-outline-primary w-100 mt-3">
                    <i class="fas fa-arrow-left me-2"></i>Seguir Comprando
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h4>Tu carrito está vacío</h4>
            <p class="text-muted">¡Agrega productos para comenzar!</p>
            <a href="{{ route('tienda.catalogo') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>Ver Productos
            </a>
        </div>
    @endif
</div>
@endsection
