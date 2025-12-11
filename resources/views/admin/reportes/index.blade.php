@extends('admin.layouts.app')

@section('title', 'Reportes - Admin')
@section('page-title', 'Reportes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Reportes</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-4x text-success mb-3"></i>
                    <h4>Reporte de Ventas</h4>
                    <p class="text-muted">Análisis de ventas por período, totales y promedios.</p>
                    <a href="{{ route('admin.reportes.ventas') }}" class="btn btn-success btn-block">
                        <i class="fas fa-eye mr-1"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-trophy fa-4x text-primary mb-3"></i>
                    <h4>Productos Más Vendidos</h4>
                    <p class="text-muted">Ranking de productos por cantidad e ingresos.</p>
                    <a href="{{ route('admin.reportes.productos') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-eye mr-1"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                    <h4>Stock Bajo</h4>
                    <p class="text-muted">Productos que necesitan reposición urgente.</p>
                    <a href="{{ route('admin.reportes.stock-bajo') }}" class="btn btn-danger btn-block">
                        <i class="fas fa-eye mr-1"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
