@extends('admin.layouts.app')

@section('title', 'Reporte de Ventas - Admin')
@section('page-title', 'Reporte de Ventas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reportes.index') }}">Reportes</a></li>
    <li class="breadcrumb-item active">Ventas</li>
@endsection

@section('content')
    <!-- Filtros de fecha -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reportes.ventas') }}" method="GET" class="form-inline">
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
                    <a href="{{ route('admin.reportes.ventas.pdf', ['fecha_desde' => $fechaDesde, 'fecha_hasta' => $fechaHasta]) }}" 
                       class="btn btn-danger" title="Exportar PDF">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </a>
                    <a href="{{ route('admin.reportes.ventas.excel', ['fecha_desde' => $fechaDesde, 'fecha_hasta' => $fechaHasta]) }}" 
                       class="btn btn-success" title="Exportar Excel">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen -->
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $resumen['total_pedidos'] }}</h3>
                    <p>Total Pedidos</p>
                </div>
                <div class="icon"><i class="fas fa-shopping-basket"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($resumen['total_ventas'], 2) }} <small>Bs</small></h3>
                    <p>Total Ventas</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($resumen['promedio_pedido'], 2) }} <small>Bs</small></h3>
                    <p>Promedio por Pedido</p>
                </div>
                <div class="icon"><i class="fas fa-chart-bar"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($resumen['total_envios'], 2) }} <small>Bs</small></h3>
                    <p>Total Envíos</p>
                </div>
                <div class="icon"><i class="fas fa-truck"></i></div>
            </div>
        </div>
    </div>

    <!-- Tabla por día -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Ventas por Día</h3>
        </div>
        <div class="card-body">
            @if($ventasPorDia->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cantidad de Pedidos</th>
                                <th>Total Ventas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventasPorDia as $dia)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($dia->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ $dia->cantidad }}</td>
                                    <td><strong>{{ number_format($dia->total, 2) }} Bs</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-area fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay ventas en el período seleccionado</p>
                </div>
            @endif
        </div>
    </div>

    <a href="{{ route('admin.reportes.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Reportes
    </a>
@endsection
