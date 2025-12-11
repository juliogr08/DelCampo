<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Proven')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <!-- Estilos personalizados - Manteniendo tu paleta natural -->
    <style>
        :root {
            --verde-bosque: #2E8B57;
            --verde-hoja: #3CB371;
            --verde-claro: #90EE90;
            --marron-tierra: #8B4513;
            --beige-arena: #F5DEB3;
            --azul-cielo: #87CEEB;
            --blanco-nube: #F8F9FA;
            --gris-arbol: #6C757D;
        }

        /* Sobrescribir colores de AdminLTE con tu paleta */
        .bg-primary { background-color: var(--verde-bosque) !important; }
        .bg-success { background-color: var(--verde-hoja) !important; }
        .bg-info { background-color: var(--azul-cielo) !important; }
        .bg-warning { background-color: var(--beige-arena) !important; color: var(--marron-tierra) !important; }
        
        .btn-primary { background-color: var(--verde-bosque); border-color: var(--verde-bosque); }
        .btn-primary:hover { background-color: var(--verde-hoja); border-color: var(--verde-hoja); }
        
        .btn-success { background-color: var(--verde-hoja); border-color: var(--verde-hoja); }
        .btn-warning { background-color: var(--beige-arena); border-color: var(--marron-tierra); color: var(--marron-tierra); }
        .btn-info { background-color: var(--azul-cielo); border-color: var(--azul-cielo); color: #333; }

        .card-primary:not(.card-outline) > .card-header { background-color: var(--verde-bosque); }
        .card-success:not(.card-outline) > .card-header { background-color: var(--verde-hoja); }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active,
        .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: var(--verde-bosque);
        }

        .page-title {
            color: var(--verde-bosque);
            border-left: 4px solid var(--verde-hoja);
            padding-left: 15px;
            margin-bottom: 25px;
        }

        .nature-badge { background-color: var(--verde-hoja); color: white; }
    </style>
    
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- User Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle"></i>
                    <span class="d-none d-md-inline ml-1">{{ Auth::user()->name }}</span>
                    <i class="fas fa-caret-down ml-1"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <div class="dropdown-item-text">
                        <div class="text-truncate">
                            <i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-footer">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ url('/') }}" class="brand-link">
            <i class="fas fa-leaf brand-icon"></i>
            <span class="brand-text font-weight-light">Proven</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Productos -->
                    <li class="nav-item">
                        <a href="{{ route('productos.index') }}" class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Productos</p>
                        </a>
                    </li>

                    <!-- Almacenes -->
                    <li class="nav-item">
                        <a href="{{ route('almacenes.index') }}" class="nav-link {{ request()->routeIs('almacenes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>Almacenes</p>
                        </a>
                    </li>

                    <!-- Transportes -->
                    <li class="nav-item">
                        <a href="{{ route('transportes.index') }}" class="nav-link {{ request()->routeIs('transportes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck"></i>
                            <p>Transportes</p>
                        </a>
                    </li>

                    <!-- Rutas -->
                    <li class="nav-item">
                        <a href="{{ route('rutas.index') }}" class="nav-link {{ request()->routeIs('rutas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-route"></i>
                            <p>Rutas</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('content-header', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb', '<li class="breadcrumb-item active">Dashboard</li>')
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Alertas -->
                @include('components.alert')
                
                <!-- Contenido principal -->
                @yield('content')
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Versión</b> 1.0.0
        </div>
        <strong>
            <i class="fas fa-earth-americas"></i>
            Proven &copy; {{ date('Y') }}
        </strong>
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- Script para modales de eliminación -->
<script>
    $(document).ready(function() {
        $('.delete-btn').on('click', function() {
            const formId = $(this).data('form');
            $('#deleteModal').modal('show');
            $('#confirmDelete').off('click').on('click', function() {
                $('#' + formId).submit();
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>