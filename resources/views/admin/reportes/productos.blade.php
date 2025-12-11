@extends('admin.layouts.app')

@section('title', 'Productos Más Vendidos - Admin')
@section('page-title', 'Productos Más Vendidos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reportes.index') }}">Reportes</a></li>
    <li class="breadcrumb-item active">Productos</li>
@endsection

@section('content')
    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reportes.productos') }}" method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label for="fecha_desde" class="mr-2">Desde:</label>
                    <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" 
                           value="{{ $fechaDesde }}">
                </div>
                <div class="form-group mr-3">
                    <label for="fecha_hasta" class="mr-2">Hasta:</label>
                    <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" 
                           value="{{ $fechaHasta }}">
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
                
                <!-- Botones de exportación -->
                <div class="btn-group ml-auto">
                    <a href="{{ route('admin.reportes.productos.pdf', ['fecha_desde' => $fechaDesde, 'fecha_hasta' => $fechaHasta]) }}" 
                       class="btn btn-danger">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </a>
                    <a href="{{ route('admin.reportes.productos.excel', ['fecha_desde' => $fechaDesde, 'fecha_hasta' => $fechaHasta]) }}" 
                       class="btn btn-success">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-trophy mr-2"></i>Top 20 Productos</h3>
        </div>
        <div class="card-body">
            @if($productos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad Vendida</th>
                                <th>Total Generado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos as $index => $producto)
                                <tr>
                                    <td>
                                        @if($index < 3)
                                            <span class="badge badge-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'info') }}">
                                                {{ $index + 1 }}
                                            </span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td><strong>{{ $producto->nombre }}</strong></td>
                                    <td>{{ number_format($producto->precio, 2) }} Bs</td>
                                    <td>
                                        <span class="badge badge-primary badge-pill">
                                            {{ $producto->cantidad_vendida }} unidades
                                        </span>
                                    </td>
                                    <td><strong class="text-success">{{ number_format($producto->total_vendido, 2) }} Bs</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay ventas en el período seleccionado</p>
                </div>
            @endif
        </div>
    </div>

    <a href="{{ route('admin.reportes.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Reportes
    </a>
@endsection
