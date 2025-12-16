@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin')
@section('page-title', 'Dashboard Estadístico')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    
    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-body py-2">
                    <form method="GET" class="d-flex align-items-center">
                        <label class="mr-2 mb-0"><i class="fas fa-calendar-alt mr-1"></i> Período:</label>
                        <select name="dias" class="form-control form-control-sm" style="width: auto;" onchange="this.form.submit()">
                            <option value="7" {{ $dias == 7 ? 'selected' : '' }}>Últimos 7 días</option>
                            <option value="30" {{ $dias == 30 ? 'selected' : '' }}>Últimos 30 días</option>
                            <option value="90" {{ $dias == 90 ? 'selected' : '' }}>Últimos 90 días</option>
                            <option value="365" {{ $dias == 365 ? 'selected' : '' }}>Último año</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3>{{ number_format($kpis['ventas_hoy'], 0) }} <small>Bs</small></h3>
                    <p>Ventas Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <a href="{{ route('admin.reportes.ventas') }}" class="small-box-footer">
                    Ver reporte <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info">
                <div class="inner">
                    <h3>{{ number_format($kpis['ventas_mes'], 0) }} <small>Bs</small></h3>
                    <p>Ventas del Mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="{{ route('admin.reportes.ventas') }}" class="small-box-footer">
                    Ver detalle <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h3>{{ $kpis['pedidos_pendientes'] }}</h3>
                    <p>Pedidos Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('admin.pedidos.index', ['estado' => 'pendiente']) }}" class="small-box-footer">
                    Ver pendientes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-danger">
                <div class="inner">
                    <h3>{{ $kpis['productos_stock_bajo'] }}</h3>
                    <p>Productos Stock Bajo</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('admin.reportes.stock-bajo') }}" class="small-box-footer">
                    Ver productos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="info-box bg-light">
                <span class="info-box-icon bg-primary"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pedidos Hoy</span>
                    <span class="info-box-number">{{ $kpis['pedidos_hoy'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box bg-light">
                <span class="info-box-icon bg-success"><i class="fas fa-calculator"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Promedio por Pedido</span>
                    <span class="info-box-number">{{ number_format($kpis['promedio_venta'], 2) }} Bs</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box bg-light">
                <span class="info-box-icon bg-info"><i class="fas fa-store"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tiendas Activas</span>
                    <span class="info-box-number">{{ $kpis['tiendas_activas'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box bg-light">
                <span class="info-box-icon bg-secondary"><i class="fas fa-store-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Tiendas</span>
                    <span class="info-box-number">{{ $kpis['tiendas_total'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

   .
    <div class="row">
        
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-area mr-2"></i>
                        Ventas por Período (Últimos {{ $dias }} días)
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="chartVentas" height="280"></canvas>
                </div>
            </div>
        </div>

     
        <div class="col-lg-4">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Estados de Pedidos
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="chartEstados" height="280"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-5">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Ventas por Categoría
                    </h3>
                </div>
                <div class="card-body">
                    @if(count($chartData['categorias']['labels']) > 0)
                        <canvas id="chartCategorias" height="280"></canvas>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-chart-pie fa-3x mb-3"></i>
                            <p>No hay datos de ventas en este período</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

      
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Últimos Pedidos
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-sm btn-primary">
                            Ver todos
                        </a>
                    </div>
                </div>
                <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                    <ul class="list-group list-group-flush">
                        @forelse($ultimosPedidos as $pedido)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('admin.pedidos.show', $pedido) }}">
                                            <strong>{{ $pedido->codigo }}</strong>
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $pedido->user->name }}</small>
                                    </div>
                                    <div class="text-right">
                                        <strong>{{ number_format($pedido->total, 2) }} Bs</strong>
                                        <br>
                                        {!! $pedido->estado_badge !!}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">
                                No hay pedidos aún
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

       
        <div class="col-lg-3">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Stock Bajo
                    </h3>
                </div>
                <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                    <ul class="list-group list-group-flush">
                        @forelse($stockBajo as $producto)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $producto->nombre }}</strong>
                                    <br>
                                    <small class="text-muted">Mínimo: {{ $producto->stock_minimo }}</small>
                                </div>
                                <span class="badge badge-danger badge-pill">
                                    {{ $producto->stock }} uds
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-success">
                                <i class="fas fa-check-circle"></i>
                                Todo el stock en orden
                            </li>
                        @endforelse
                    </ul>
                </div>
                @if(count($stockBajo) > 0)
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.solicitudes.create') }}" class="btn btn-sm btn-danger">
                            <i class="fas fa-plus"></i> Crear Solicitud
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const ventasData = @json($chartData['ventasPorDia']);
    const categoriasData = @json($chartData['categorias']);
    const estadosData = @json($chartData['estados']);

    
    const ctxVentas = document.getElementById('chartVentas').getContext('2d');
    new Chart(ctxVentas, {
        type: 'line',
        data: {
            labels: ventasData.labels,
            datasets: [{
                label: 'Ventas (Bs)',
                data: ventasData.ventas,
                borderColor: '#4A7C23',
                backgroundColor: 'rgba(74, 124, 35, 0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 3,
                pointBackgroundColor: '#4A7C23',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Bs ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Bs ' + value;
                        }
                    }
                }
            }
        }
    });

    
    if(estadosData.labels.length > 0) {
        const ctxEstados = document.getElementById('chartEstados').getContext('2d');
        new Chart(ctxEstados, {
            type: 'doughnut',
            data: {
                labels: estadosData.labels,
                datasets: [{
                    data: estadosData.data,
                    backgroundColor: estadosData.colores,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    
    if(categoriasData.labels.length > 0) {
        const ctxCategorias = document.getElementById('chartCategorias').getContext('2d');
        new Chart(ctxCategorias, {
            type: 'doughnut',
            data: {
                labels: categoriasData.labels,
                datasets: [{
                    data: categoriasData.data,
                    backgroundColor: categoriasData.colores,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return context.label + ': Bs ' + value.toFixed(2) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
