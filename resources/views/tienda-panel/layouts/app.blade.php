<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel de Tienda')</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        :root {
            --azul-tienda: #1565C0;
            --azul-oscuro: #0D47A1;
            --azul-claro: #42A5F5;
            --azul-suave: #E3F2FD;
            --naranja-accion: #FF6F00;
            --verde-exito: #2E7D32;
            --crema: #FDF8F3;
            --blanco: #FFFFFF;
            --texto-oscuro: #2C2C2C;
            --texto-gris: #666666;
        }

        body { font-family: 'Source Sans Pro', sans-serif; }

        .main-sidebar {
            background: linear-gradient(180deg, var(--azul-oscuro) 0%, var(--azul-tienda) 100%) !important;
        }
        
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: var(--azul-claro) !important;
            color: #fff !important;
        }

        .brand-link {
            background: rgba(0,0,0,0.15) !important;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        }

        .nav-sidebar .nav-link { color: rgba(255,255,255,0.85) !important; }
        .nav-sidebar .nav-link:hover { background-color: rgba(255,255,255,0.1) !important; }
        .nav-header { color: rgba(255,255,255,0.5) !important; }

        .content-wrapper { background-color: var(--crema) !important; }

        .btn-primary { 
            background-color: var(--azul-tienda) !important; 
            border-color: var(--azul-tienda) !important; 
        }
        .btn-primary:hover { 
            background-color: var(--azul-oscuro) !important; 
            border-color: var(--azul-oscuro) !important; 
        }

        .btn-success { background-color: var(--verde-exito) !important; border-color: var(--verde-exito) !important; }
        .btn-warning { background-color: var(--naranja-accion) !important; border-color: var(--naranja-accion) !important; color: white !important; }

        .card { 
            border: none !important; 
            border-radius: 12px !important; 
            box-shadow: 0 2px 12px rgba(0,0,0,0.08) !important; 
        }
        .card-header { 
            background-color: var(--blanco) !important; 
            border-bottom: 1px solid rgba(0,0,0,0.05) !important;
            border-radius: 12px 12px 0 0 !important;
        }

        .small-box.bg-info { background: linear-gradient(135deg, var(--azul-tienda) 0%, var(--azul-claro) 100%) !important; }
        .small-box.bg-success { background: linear-gradient(135deg, var(--verde-exito) 0%, #43A047 100%) !important; }
        .small-box.bg-warning { background: linear-gradient(135deg, var(--naranja-accion) 0%, #FF8F00 100%) !important; }
        .small-box.bg-danger { background: linear-gradient(135deg, #C62828 0%, #E53935 100%) !important; }

        .table thead th { 
            background-color: var(--azul-suave) !important; 
            color: var(--texto-oscuro) !important;
            font-weight: 600 !important;
            border: none !important;
        }

        .badge-primary, .bg-primary { background-color: var(--azul-tienda) !important; }
        .badge-success, .bg-success { background-color: var(--verde-exito) !important; }

        .form-control:focus {
            border-color: var(--azul-tienda) !important;
            box-shadow: 0 0 0 0.2rem rgba(21,101,192,0.25) !important;
        }

        .main-footer { 
            background-color: var(--azul-suave) !important; 
            border-top: 1px solid rgba(0,0,0,0.05) !important;
        }

        .tienda-status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .tienda-status-pendiente { background-color: #FFF3CD; color: #856404; }
        .tienda-status-activa { background-color: #D4EDDA; color: #155724; }
        .tienda-status-suspendida { background-color: #F8D7DA; color: #721C24; }

        .alert-stock-bajo {
            background: linear-gradient(135deg, #FFEBEE 0%, #FFCDD2 100%);
            border: 1px solid #EF9A9A;
            border-radius: 8px;
        }
    </style>
    
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('tienda.panel.dashboard') }}" class="nav-link">
                    <i class="fas fa-store text-primary"></i> Mi Tienda
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('tienda.home') }}" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Ver E-commerce
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            @php $tienda = auth()->user()->tienda; @endphp
            @if($tienda)
                <li class="nav-item mr-3">
                    <span class="tienda-status-badge tienda-status-{{ $tienda->estado }}">
                        @if($tienda->estado === 'pendiente')
                            <i class="fas fa-clock mr-1"></i> Pendiente
                        @elseif($tienda->estado === 'activa')
                            <i class="fas fa-check-circle mr-1"></i> Activa
                        @else
                            <i class="fas fa-ban mr-1"></i> Suspendida
                        @endif
                    </span>
                </li>
            @endif
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    @if($tienda && $tienda->logo_path)
                        <img src="{{ asset('storage/' . $tienda->logo_path) }}" class="img-circle" style="width: 28px; height: 28px; object-fit: cover;">
                    @else
                        <i class="fas fa-store fa-lg"></i>
                    @endif
                    <span class="d-none d-md-inline ml-1">{{ $tienda->nombre ?? Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <span class="dropdown-item-text">
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </span>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('tienda.panel.configuracion') }}" class="dropdown-item">
                        <i class="fas fa-cog mr-2"></i> Configuración
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('tienda.panel.dashboard') }}" class="brand-link">
            @if($tienda && $tienda->logo_path)
                <img src="{{ asset('storage/' . $tienda->logo_path) }}" class="brand-image img-circle elevation-3" style="opacity: .8; width: 33px; height: 33px; object-fit: cover;">
            @else
                <i class="fas fa-store brand-image ml-3" style="font-size: 1.5rem;"></i>
            @endif
            <span class="brand-text font-weight-light">{{ Str::limit($tienda->nombre ?? 'Mi Tienda', 15) }}</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    
                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.dashboard') }}" class="nav-link {{ request()->routeIs('tienda.panel.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">MI CATÁLOGO</li>

                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.productos.index') }}" class="nav-link {{ request()->routeIs('tienda.panel.productos.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Mis Productos</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.almacenes.index') }}" class="nav-link {{ request()->routeIs('tienda.panel.almacenes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>Mis Almacenes</p>
                        </a>
                    </li>

                    <li class="nav-header">CATÁLOGO ADMIN</li>

                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.catalogo-admin') }}" class="nav-link {{ request()->routeIs('tienda.panel.catalogo-admin*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-store-alt"></i>
                            <p>Ver Productos</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.proponer-producto') }}" class="nav-link {{ request()->routeIs('tienda.panel.proponer-producto*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-lightbulb"></i>
                            <p>Proponer Producto</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.mis-propuestas') }}" class="nav-link {{ request()->routeIs('tienda.panel.mis-propuestas') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Mis Propuestas</p>
                        </a>
                    </li>

                    <li class="nav-header">VENTAS</li>

                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.ventas.index') }}" class="nav-link {{ request()->routeIs('tienda.panel.ventas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Mis Ventas</p>
                        </a>
                    </li>

                    <li class="nav-header">CAJA</li>

                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.caja') }}" class="nav-link {{ request()->routeIs('tienda.panel.caja') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Caja del Día</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.caja.historial') }}" class="nav-link {{ request()->routeIs('tienda.panel.caja.historial') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Historial Cierres</p>
                        </a>
                    </li>

                    <li class="nav-header">INVENTARIO</li>

                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.solicitudes.index') }}" class="nav-link {{ request()->routeIs('tienda.panel.solicitudes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck-loading"></i>
                            <p>Solicitar Stock</p>
                        </a>
                    </li>

                    <li class="nav-header">AJUSTES</li>

                    <li class="nav-item">
                        <a href="{{ route('tienda.panel.configuracion') }}" class="nav-link {{ request()->routeIs('tienda.panel.configuracion') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Configuración</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-dark">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <i class="fas fa-store text-primary"></i> Panel de Tienda
        </div>
        <strong>{{ $tienda->nombre ?? 'Mi Tienda' }} &copy; {{ date('Y') }}</strong>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>
