@extends('admin.layouts.app')

@section('title', 'Stock Bajo - Admin')
@section('page-title', 'Productos con Stock Bajo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reportes.index') }}">Reportes</a></li>
    <li class="breadcrumb-item active">Stock Bajo</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-exclamation-triangle text-danger mr-2"></i>Productos que Necesitan Reposición</h3>
            <div class="card-tools">
                <a href="{{ route('admin.reportes.stock-bajo.pdf') }}" class="btn btn-danger btn-sm mr-1">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <a href="{{ route('admin.reportes.stock-bajo.excel') }}" class="btn btn-success btn-sm mr-1">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
                <a href="{{ route('admin.solicitudes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Crear Solicitud
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($productos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Déficit</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos as $producto)
                                @php
                                    $deficit = $producto->stock_minimo - $producto->stock;
                                @endphp
                                <tr>
                                    <td><strong>{{ $producto->nombre }}</strong></td>
                                    <td>{{ $producto->categoria_nombre }}</td>
                                    <td>
                                        <span class="badge badge-{{ $producto->stock <= 0 ? 'danger' : 'warning' }} badge-pill">
                                            {{ $producto->stock }}
                                        </span>
                                    </td>
                                    <td>{{ $producto->stock_minimo }}</td>
                                    <td>
                                        <strong class="text-danger">-{{ $deficit }}</strong>
                                    </td>
                                    <td>
                                        @if($producto->stock <= 0)
                                            <span class="badge badge-danger">SIN STOCK</span>
                                        @else
                                            <span class="badge badge-warning">STOCK BAJO</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4 class="text-success">¡Todo en orden!</h4>
                    <p class="text-muted">No hay productos con stock bajo en este momento.</p>
                </div>
            @endif
        </div>
    </div>

    <a href="{{ route('admin.reportes.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Reportes
    </a>
@endsection
