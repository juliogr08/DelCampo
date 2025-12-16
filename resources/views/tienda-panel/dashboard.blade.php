@extends('tienda-panel.layouts.app')

@section('title', 'Dashboard - ' . $tienda->nombre)
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    @if($tienda->estado === 'pendiente')
        <div class="alert alert-warning">
            <i class="fas fa-clock mr-2"></i>
            <strong>Tu tienda está pendiente de aprobación.</strong> 
            Para activarla, realiza tu primera solicitud de productos al administrador.
            <a href="{{ route('tienda.panel.solicitudes.create') }}" class="btn btn-sm btn-primary ml-3">
                <i class="fas fa-plus mr-1"></i> Hacer Primera Solicitud
            </a>
        </div>
    @endif

    @if($productosStockBajo->count() > 0)
        <div class="card bg-gradient-danger mb-4" style="border-left: 5px solid #721c24; animation: pulse 2s infinite;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="d-flex align-items-center justify-content-center bg-white rounded-circle" style="width: 70px; height: 70px;">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                    </div>
                    <div class="col text-white">
                        <h4 class="mb-1">
                            <i class="fas fa-bell mr-2"></i>¡Atención! Stock Bajo
                        </h4>
                        <p class="mb-2">
                            <strong>{{ $productosStockBajo->count() }} producto(s)</strong> tienen stock bajo 
                            (límite: {{ $tienda->limite_stock_bajo ?? 5 }} unidades) - <em>Haz clic en uno para solicitar:</em>
                        </p>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($productosStockBajo as $producto)
                                <a href="{{ route('tienda.panel.solicitudes.create', ['producto_id' => $producto->id]) }}" 
                                   class="badge badge-light mr-1 mb-1 text-dark" 
                                   style="cursor: pointer; text-decoration: none; transition: all 0.2s;"
                                   onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.2)';"
                                   onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                                    <i class="fas fa-truck-loading mr-1"></i>
                                    {{ Str::limit($producto->nombre, 20) }} 
                                    <strong class="text-danger">({{ $producto->stock }})</strong>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-light btn-lg shadow" data-toggle="modal" data-target="#modalStockBajo">
                            <i class="fas fa-truck-loading mr-1"></i> Solicitar Stock Ahora
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal para seleccionar producto -->
        <div class="modal fade" id="modalStockBajo" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Productos con Stock Bajo
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-3">Selecciona un producto para solicitar stock:</p>
                        <div class="list-group">
                            @foreach($productosStockBajo as $producto)
                                <a href="{{ route('tienda.panel.solicitudes.create', ['producto_id' => $producto->id]) }}" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($producto->imagen)
                                            <img src="{{ $producto->imagen_url }}" class="rounded mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $producto->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $producto->categoria_nombre }}</small>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-danger badge-pill">Stock: {{ $producto->stock }}</span>
                                        <br>
                                        <small class="text-success">
                                            <i class="fas fa-arrow-right"></i> Solicitar
                                        </small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            @keyframes pulse {
                0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
                50% { box-shadow: 0 0 20px 5px rgba(220, 53, 69, 0.6); }
            }
        </style>
    @endif

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalProductos }}</h3>
                    <p>Productos Totales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="{{ route('tienda.panel.productos.index') }}" class="small-box-footer">
                    Ver productos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $productosActivos }}</h3>
                    <p>Productos Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('tienda.panel.productos.index') }}" class="small-box-footer">
                    Ver activos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $ventasHoy }}</h3>
                    <p>Ventas Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('tienda.panel.ventas.index') }}" class="small-box-footer">
                    Ver ventas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($ingresosMes, 2) }} <small>Bs</small></h3>
                    <p>Ingresos del Mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <a href="{{ route('tienda.panel.ventas.index') }}" class="small-box-footer">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-box mr-2"></i>Últimos Productos</h3>
                    <div class="card-tools">
                        <a href="{{ route('tienda.panel.productos.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($ultimosProductos->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosProductos as $producto)
                                    <tr>
                                        <td>{{ Str::limit($producto->nombre, 25) }}</td>
                                        <td>{{ number_format($producto->precio, 2) }} Bs</td>
                                        <td>
                                            @if($producto->stock <= $tienda->limite_stock_bajo)
                                                <span class="text-danger font-weight-bold">{{ $producto->stock }}</span>
                                            @else
                                                {{ $producto->stock }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($producto->activo)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No tienes productos aún</p>
                            <a href="{{ route('tienda.panel.productos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i> Crear mi primer producto
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-truck-loading mr-2"></i>Últimas Solicitudes</h3>
                    <div class="card-tools">
                        <a href="{{ route('tienda.panel.solicitudes.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nueva
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($ultimasSolicitudes->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasSolicitudes as $solicitud)
                                    <tr>
                                        <td>{{ Str::limit($solicitud->producto->nombre ?? 'N/A', 20) }}</td>
                                        <td>{{ $solicitud->cantidad_solicitada }}</td>
                                        <td>{!! $solicitud->estado_badge !!}</td>
                                        <td>{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No has realizado solicitudes aún</p>
                            <a href="{{ route('tienda.panel.solicitudes.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i> Hacer primera solicitud
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Información de la Tienda</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            @if($tienda->logo_path)
                                <img src="{{ asset('storage/' . $tienda->logo_path) }}" alt="Logo" class="img-fluid rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 100px; height: 100px;">
                                    <i class="fas fa-store fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <h4>{{ $tienda->nombre }}</h4>
                            <p class="text-muted mb-2">{{ $tienda->descripcion ?? 'Sin descripción' }}</p>
                            <p class="mb-1"><i class="fas fa-phone mr-2"></i>{{ $tienda->telefono }}</p>
                            <p class="mb-1"><i class="fas fa-map-marker-alt mr-2"></i>{{ $tienda->direccion }}</p>
                        </div>
                        <div class="col-md-5">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="h3 mb-0 text-primary">{{ $totalProductos }}</div>
                                    <small class="text-muted">Productos</small>
                                </div>
                                <div class="col-4">
                                    <div class="h3 mb-0 text-success">{{ $tienda->almacenes()->count() }}</div>
                                    <small class="text-muted">Almacenes</small>
                                </div>
                                <div class="col-4">
                                    <div class="h3 mb-0 text-warning">{{ $solicitudesPendientes }}</div>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
