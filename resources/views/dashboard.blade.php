@extends('layouts.app')

@section('title', 'Dashboard - Proven')

@section('content-header', 'Panel de Control')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-3 col-6">
        
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ \App\Models\Producto::count() }}</h3>
                <p>Productos</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <a href="{{ route('productos.index') }}" class="small-box-footer">
                Más info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ \App\Models\Almacen::count() }}</h3>
                <p>Almacenes</p>
            </div>
            <div class="icon">
                <i class="fas fa-warehouse"></i>
            </div>
            <a href="{{ route('almacenes.index') }}" class="small-box-footer">
                Más info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ \App\Models\Transporte::count() }}</h3>
                <p>Transportes</p>
            </div>
            <div class="icon">
                <i class="fas fa-truck"></i>
            </div>
            <a href="{{ route('transportes.index') }}" class="small-box-footer">
                Más info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ \App\Models\Ruta::count() }}</h3>
                <p>Rutas</p>
            </div>
            <div class="icon">
                <i class="fas fa-route"></i>
            </div>
            <a href="{{ route('rutas.index') }}" class="small-box-footer">
                Más info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle me-2"></i>Información del Sistema
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success"><i class="fas fa-check-circle me-2"></i>Funcionalidades Principales</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-cube me-2 text-muted"></i>Gestión completa de productos</li>
                            <li><i class="fas fa-map-marker-alt me-2 text-muted"></i>Control de almacenes</li>
                            <li><i class="fas fa-thermometer-half me-2 text-muted"></i>Monitoreo de temperatura</li>
                            <li><i class="fas fa-shipping-fast me-2 text-muted"></i>Seguimiento de transporte</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success"><i class="fas fa-chart-line me-2"></i>Beneficios</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-bolt me-2 text-muted"></i>Procesos optimizados</li>
                            <li><i class="fas fa-eye me-2 text-muted"></i>Trazabilidad completa</li>
                            <li><i class="fas fa-leaf me-2 text-muted"></i>Gestión sostenible</li>
                            <li><i class="fas fa-clock me-2 text-muted"></i>Tiempos reducidos</li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-4 p-3 rounded" style="background-color: var(--beige-arena);">
                    <h6 class="text-success mb-3"><i class="fas fa-seedling me-2"></i>Compromiso Ambiental</h6>
                    <p class="mb-0 text-muted">
                        Nuestro sistema está diseñado con enfoque en la sostenibilidad, optimizando rutas para reducir 
                        emisiones y promoviendo prácticas logísticas responsables con el medio ambiente.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection