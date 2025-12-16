<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Productos frescos del campo directo a tu mesa. Papas, choclo, verduras y más.">
    <title>@yield('title', 'Del Campo - Productos Frescos')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    
    <style>
        :root {
            /* Paleta del Campo - Verde y Café */
            --verde-bosque: #2D5016;
            --verde-campo: #4A7C23;
            --verde-hoja: #6B9B37;
            --verde-claro: #8FBC5A;
            --verde-suave: #E8F5E0;
            --cafe-tierra: #5D4037;
            --cafe-claro: #8D6E63;
            --cafe-suave: #D7CCC8;
            --beige-trigo: #F5E6D3;
            --crema: #FDF8F3;
            --blanco: #FFFFFF;
            --texto-oscuro: #2C2C2C;
            --texto-gris: #555555;
            --naranja-cosecha: #E67E22;
        }
        
        * { 
            font-family: 'Nunito', sans-serif; 
        }
        
        body { 
            background-color: var(--crema); 
            color: var(--texto-oscuro);
        }
        
        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--verde-bosque) 0%, var(--verde-campo) 100%) !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.15);
        }
        .navbar-brand { 
            font-weight: 800; 
            color: var(--blanco) !important; 
            font-size: 1.5rem;
        }
        .navbar-brand i { color: var(--verde-claro); }
        .nav-link { 
            font-weight: 600; 
            color: rgba(255,255,255,0.9) !important;
            transition: all 0.2s;
        }
        .nav-link:hover { 
            color: var(--blanco) !important;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .navbar-toggler { border-color: rgba(255,255,255,0.5); }
        .navbar-toggler-icon { filter: brightness(100); }
        
        /* Cards de producto */
        .product-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            background: var(--blanco);
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(45, 80, 22, 0.2);
        }
        .product-card img {
            height: 200px;
            object-fit: cover;
        }
        .product-price {
            color: var(--verde-campo);
            font-weight: 800;
            font-size: 1.4rem;
        }
        
        /* Botones */
        .btn-primary {
            background: linear-gradient(135deg, var(--verde-campo) 0%, var(--verde-hoja) 100%);
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--verde-bosque) 0%, var(--verde-campo) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 124, 35, 0.4);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--verde-campo);
            color: var(--verde-campo);
            font-weight: 600;
        }
        .btn-outline-primary:hover {
            background-color: var(--verde-campo);
            border-color: var(--verde-campo);
            color: var(--blanco);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--verde-hoja) 0%, var(--verde-claro) 100%);
            border: none;
        }

        .btn-warning {
            background: var(--naranja-cosecha);
            border: none;
            color: var(--blanco);
        }
        
        /* Badge carrito */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--naranja-cosecha);
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 0.75rem;
            font-weight: 800;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        /* Footer */
        footer {
            background: linear-gradient(180deg, var(--cafe-tierra) 0%, #3E2723 100%);
            color: var(--blanco);
            padding: 50px 0 30px;
            margin-top: 60px;
        }
        footer h5 { 
            color: var(--beige-trigo); 
            font-weight: 700;
            margin-bottom: 1rem;
        }
        footer p, footer a, footer li {
            color: rgba(255,255,255,0.85) !important;
        }
        footer a:hover {
            color: var(--verde-claro) !important;
            text-decoration: none;
        }
        footer hr {
            border-color: rgba(255,255,255,0.2);
        }
        
        /* Hero */
        .hero {
            background: linear-gradient(135deg, var(--verde-bosque) 0%, var(--verde-campo) 50%, var(--verde-hoja) 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5C30 5 35 15 35 20C35 25 30 28 30 28C30 28 25 25 25 20C25 15 30 5 30 5Z' fill='rgba(255,255,255,0.05)'/%3E%3C/svg%3E");
            opacity: 0.3;
        }
        .hero h1 { font-weight: 800; text-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .hero .btn-light {
            background: var(--blanco);
            color: var(--verde-bosque);
            font-weight: 700;
            padding: 12px 30px;
            border-radius: 25px;
        }
        .hero .btn-light:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        /* Categorías */
        .category-badge {
            display: inline-block;
            padding: 10px 20px;
            background: var(--blanco);
            border: 2px solid var(--verde-suave);
            border-radius: 25px;
            margin: 5px;
            text-decoration: none;
            color: var(--texto-oscuro);
            font-weight: 600;
            transition: all 0.3s;
        }
        .category-badge:hover, .category-badge.active {
            background: var(--verde-campo);
            border-color: var(--verde-campo);
            color: white;
            transform: translateY(-2px);
        }

        /* Cards generales */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        .card-header {
            background: var(--verde-suave);
            border-bottom: none;
            font-weight: 700;
            color: var(--verde-bosque);
        }

        /* Alertas */
        .alert-success {
            background: var(--verde-suave);
            border-color: var(--verde-campo);
            color: var(--verde-bosque);
        }
        .alert-danger {
            background: #FFEBEE;
            border-color: #C0392B;
            color: #B71C1C;
        }

        /* Forms */
        .form-control:focus {
            border-color: var(--verde-campo);
            box-shadow: 0 0 0 0.25rem rgba(74, 124, 35, 0.25);
        }
        .form-select:focus {
            border-color: var(--verde-campo);
            box-shadow: 0 0 0 0.25rem rgba(74, 124, 35, 0.25);
        }

        /* Info cards */
        .info-card {
            background: var(--blanco);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s;
        }
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .info-card i {
            color: var(--verde-campo);
        }

        /* Breadcrumb */
        .breadcrumb {
            background: var(--verde-suave);
            padding: 12px 20px;
            border-radius: 10px;
        }
        .breadcrumb-item a {
            color: var(--verde-campo);
            font-weight: 600;
        }

        /* Badges */
        .badge.bg-secondary {
            background-color: var(--cafe-claro) !important;
        }
        .badge.bg-success {
            background-color: var(--verde-campo) !important;
        }
        .badge.bg-warning {
            background-color: var(--naranja-cosecha) !important;
            color: white !important;
        }

        /* Tables */
        .table thead th {
            background: var(--verde-suave);
            color: var(--verde-bosque);
            font-weight: 700;
            border: none;
        }

        /* Dropdown menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            border-radius: 12px;
        }
        .dropdown-item {
            font-weight: 500;
            padding: 10px 20px;
        }
        .dropdown-item:hover {
            background: var(--verde-suave);
            color: var(--verde-bosque);
        }

        /* Paginación */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }
        .pagination svg {
            width: 20px;
            height: 20px;
        }
        .pagination > * {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .pagination .page-link,
        .pagination > a,
        .pagination > span {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            border: 1px solid var(--verde-suave);
            background: var(--blanco);
            color: var(--verde-campo);
            transition: all 0.2s;
        }
        .pagination > a:hover,
        .pagination .page-link:hover {
            background: var(--verde-campo);
            color: var(--blanco);
            border-color: var(--verde-campo);
        }
        .pagination > span[aria-current="page"] > span,
        .pagination .page-item.active .page-link {
            background: var(--verde-campo);
            color: var(--blanco);
            border-color: var(--verde-campo);
        }
        .pagination > span[aria-disabled="true"],
        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }
        /* Ocultar texto "Previous" y "Next" en botones */
        nav[role="navigation"] > div:first-child {
            display: none;
        }
        nav[role="navigation"] > div:last-child {
            width: 100%;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('tienda.home') }}">
                <i class="fas fa-seedling me-2"></i>Del Campo
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ route('tienda.home') }}">
                            <i class="fas fa-home me-1"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ route('tienda.catalogo') }}">
                            <i class="fas fa-carrot me-1"></i> Productos
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Carrito -->
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative px-3" href="{{ route('tienda.carrito') }}">
                            <i class="fas fa-shopping-basket fa-lg"></i>
                            @php $carritoCount = array_sum(session('carrito', [])); @endphp
                            @if($carritoCount > 0)
                                <span class="cart-badge">{{ $carritoCount }}</span>
                            @endif
                        </a>
                    </li>
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Ingresar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light btn-sm px-3 ms-2" href="{{ route('register') }}" 
                               style="color: var(--verde-bosque); font-weight: 700;">
                                Registrarse
                            </a>
                        </li>
                    @else
                        @if(Auth::user()->tienda)
                            <!-- Botón para dueños de tienda -->
                            <li class="nav-item me-2">
                                <a href="{{ route('tienda.panel.dashboard') }}" class="btn btn-warning btn-sm" 
                                   style="font-weight: 700;">
                                    <i class="fas fa-tachometer-alt me-1"></i> Mi Panel
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-3" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->tienda)
                                    <li><a class="dropdown-item" href="{{ route('tienda.panel.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2 text-warning"></i>Panel de Tienda
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                @if(Auth::user()->hasRole('admin'))
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-cog me-2 text-primary"></i>Panel Admin
                                    </a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('tienda.mis-pedidos') }}">
                                        <i class="fas fa-box me-2 text-muted"></i>Mis Pedidos
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('tienda.mi-perfil') }}">
                                        <i class="fas fa-user me-2 text-muted"></i>Mi Perfil
                                    </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2 text-muted"></i>Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alertas -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Contenido -->
    @yield('content')

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-seedling me-2"></i>Del Campo</h5>
                    <p>Productos frescos del campo boliviano directo a tu mesa. Calidad y frescura garantizadas.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white fs-5"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('tienda.catalogo') }}"><i class="fas fa-angle-right me-2"></i>Ver Productos</a></li>
                        <li class="mb-2"><a href="{{ route('tienda.carrito') }}"><i class="fas fa-angle-right me-2"></i>Mi Carrito</a></li>
                        @auth
                            <li class="mb-2"><a href="{{ route('tienda.mis-pedidos') }}"><i class="fas fa-angle-right me-2"></i>Mis Pedidos</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contacto</h5>
                    <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Santa Cruz de la Sierra, Bolivia</p>
                    <p class="mb-2"><i class="fas fa-phone me-2"></i>+591 70000000</p>
                    <p class="mb-2"><i class="fas fa-envelope me-2"></i>info@delcampo.com</p>
                    <p class="mb-0"><i class="fas fa-clock me-2"></i>Lun - Sáb: 7:00 - 20:00</p>
                </div>
            </div>
            <hr>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Del Campo. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small>Productos frescos del campo boliviano 🌽🥔🥕</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
