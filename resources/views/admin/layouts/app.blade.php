<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - Del Campo')</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        :root {
            /* Paleta del Campo - Verde y Café */
            --verde-bosque: #2D5016;
            --verde-campo: #4A7C23;
            --verde-hoja: #6B9B37;
            --verde-claro: #8FBC5A;
            --cafe-tierra: #5D4037;
            --cafe-claro: #8D6E63;
            --beige-trigo: #F5E6D3;
            --crema: #FDF8F3;
            --blanco: #FFFFFF;
            --texto-oscuro: #2C2C2C;
            --texto-gris: #666666;
        }

        body { font-family: 'Source Sans Pro', sans-serif; }

        /* Sidebar verde bosque */
        .main-sidebar {
            background: linear-gradient(180deg, var(--verde-bosque) 0%, var(--verde-campo) 100%) !important;
        }
        
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: var(--verde-hoja) !important;
            color: #fff !important;
        }

        .brand-link {
            background: rgba(0,0,0,0.15) !important;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        }

        .nav-sidebar .nav-link { color: rgba(255,255,255,0.85) !important; }
        .nav-sidebar .nav-link:hover { background-color: rgba(255,255,255,0.1) !important; }
        .nav-header { color: rgba(255,255,255,0.5) !important; }

        /* Contenido con fondo crema */
        .content-wrapper { background-color: var(--crema) !important; }

        /* Botones */
        .btn-primary { 
            background-color: var(--verde-campo) !important; 
            border-color: var(--verde-campo) !important; 
        }
        .btn-primary:hover { 
            background-color: var(--verde-bosque) !important; 
            border-color: var(--verde-bosque) !important; 
        }

        .btn-success { background-color: var(--verde-hoja) !important; border-color: var(--verde-hoja) !important; }
        .btn-info { background-color: var(--cafe-claro) !important; border-color: var(--cafe-claro) !important; }
        .btn-warning { background-color: #E6A23C !important; border-color: #E6A23C !important; }
        .btn-danger { background-color: #C0392B !important; border-color: #C0392B !important; }

        .btn-outline-primary { 
            color: var(--verde-campo) !important; 
            border-color: var(--verde-campo) !important; 
        }
        .btn-outline-primary:hover { 
            background-color: var(--verde-campo) !important; 
            color: #fff !important; 
        }

        /* Cards */
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

        /* Small boxes */
        .small-box.bg-info { background: linear-gradient(135deg, var(--verde-campo) 0%, var(--verde-hoja) 100%) !important; }
        .small-box.bg-success { background: linear-gradient(135deg, var(--verde-hoja) 0%, var(--verde-claro) 100%) !important; }
        .small-box.bg-warning { background: linear-gradient(135deg, #E6A23C 0%, #F0C674 100%) !important; }
        .small-box.bg-danger { background: linear-gradient(135deg, #C0392B 0%, #E74C3C 100%) !important; }

        /* Info boxes */
        .info-box-icon.bg-primary { background-color: var(--verde-campo) !important; }
        .info-box-icon.bg-success { background-color: var(--verde-hoja) !important; }
        .info-box-icon.bg-info { background-color: var(--cafe-claro) !important; }
        .info-box-icon.bg-warning { background-color: #E6A23C !important; }

        /* Tables */
        .table thead th { 
            background-color: var(--beige-trigo) !important; 
            color: var(--texto-oscuro) !important;
            font-weight: 600 !important;
            border: none !important;
        }
        .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(107, 155, 55, 0.05) !important; }

        /* Badges */
        .badge-primary, .bg-primary { background-color: var(--verde-campo) !important; }
        .badge-success, .bg-success { background-color: var(--verde-hoja) !important; }
        .badge-info, .bg-info { background-color: var(--cafe-claro) !important; }

        /* Forms */
        .form-control:focus {
            border-color: var(--verde-campo) !important;
            box-shadow: 0 0 0 0.2rem rgba(74,124,35,0.25) !important;
        }

        /* Footer */
        .main-footer { 
            background-color: var(--beige-trigo) !important; 
            border-top: 1px solid rgba(0,0,0,0.05) !important;
        }

        /* Alertas mejoradas */
        .alert-success { background-color: #D4EDDA; border-color: var(--verde-hoja); color: var(--verde-bosque); }
        .alert-danger { background-color: #F8D7DA; border-color: #C0392B; color: #721C24; }
        .alert-warning { background-color: #FFF3CD; border-color: #E6A23C; color: #856404; }
        .alert-info { background-color: #D1ECF1; border-color: var(--cafe-claro); color: var(--cafe-tierra); }
    </style>
    
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fas fa-leaf text-success"></i> Panel Admin
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('tienda.home') }}" class="nav-link" target="_blank">
                    <i class="fas fa-store"></i> Ver Tienda
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle fa-lg"></i>
                    <span class="d-none d-md-inline ml-1">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <span class="dropdown-item-text">
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </span>
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

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <i class="fas fa-seedling brand-image ml-3" style="font-size: 1.5rem;"></i>
            <span class="brand-text font-weight-light">Del Campo</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">CATÁLOGO</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.productos.index') }}" class="nav-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-carrot"></i>
                            <p>Productos</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.almacenes.index') }}" class="nav-link {{ request()->routeIs('admin.almacenes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>Almacenes</p>
                        </a>
                    </li>

                    <li class="nav-header">VENTAS</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pedidos.index') }}" class="nav-link {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-basket"></i>
                            <p>Pedidos</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.clientes.index') }}" class="nav-link {{ request()->routeIs('admin.clientes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Clientes</p>
                        </a>
                    </li>

                    <li class="nav-header">INVENTARIO</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.solicitudes.index') }}" class="nav-link {{ request()->routeIs('admin.solicitudes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck-loading"></i>
                            <p>Solicitudes</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.reportes.index') }}" class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>Reportes</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
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
            <i class="fas fa-leaf text-success"></i> Productos del Campo
        </div>
        <strong>Del Campo &copy; {{ date('Y') }}</strong>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>
