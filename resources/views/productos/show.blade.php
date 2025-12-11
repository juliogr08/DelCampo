@extends('layouts.app')

@section('title', 'Detalles del Producto')

@section('content-header', 'Detalles del Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Detalles</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-box me-2"></i>Información del Producto
        </h3>
        <div class="card-tools">
            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID:</th>
                        <td>#{{ $producto->id }}</td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $producto->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Código de Barras:</th>
                        <td>
                            @if($producto->codigo_barras)
                                <span class="badge bg-light text-dark">{{ $producto->codigo_barras }}</span>
                            @else
                                <span class="text-muted">No asignado</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Precio:</th>
                        <td>
                            <span class="fw-bold text-success">
                                ${{ number_format(floatval($producto->precio ?? 0), 2) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Lote:</th>
                        <td>
                            <span class="badge nature-badge">{{ $producto->lote }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Stock:</th>
                        <td>
                            @php
                                $stock = intval($producto->stock ?? 0);
                            @endphp
                            @if($stock > 20)
                                <span class="badge bg-success">{{ $stock }} unidades</span>
                            @elseif($stock > 5)
                                <span class="badge bg-warning text-dark">{{ $stock }} unidades</span>
                            @else
                                <span class="badge bg-danger">{{ $stock }} unidades</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Fecha Vencimiento:</th>
                        <td>
                            @if($producto->fecha_vencimiento)
                                @php
                                    try {
                                        $fechaVencimiento = \Carbon\Carbon::parse($producto->fecha_vencimiento);
                                        echo $fechaVencimiento->format('d/m/Y');
                                        
                                        if($fechaVencimiento->isPast()) {
                                            echo '<span class="badge bg-danger ms-2">Vencido</span>';
                                        } elseif($fechaVencimiento->diffInDays(now()) <= 30) {
                                            echo '<span class="badge bg-warning ms-2">Por vencer</span>';
                                        }
                                    } catch (Exception $e) {
                                        echo '<span class="text-muted">Fecha inválida</span>';
                                    }
                                @endphp
                            @else
                                <span class="text-muted">No asignada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @php
                                $stock = intval($producto->stock ?? 0);
                            @endphp
                            @if($stock > 0)
                                <span class="badge bg-success">Disponible</span>
                            @else
                                <span class="badge bg-danger">Agotado</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Valor Total:</th>
                        <td>
                            <span class="fw-bold text-primary">
                                ${{ number_format(floatval($producto->precio ?? 0) * intval($producto->stock ?? 0), 2) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($producto->descripcion)
        <div class="row mt-4">
            <div class="col-12">
                <h5><i class="fas fa-file-alt me-2"></i>Descripción</h5>
                <div class="p-3 bg-light rounded">
                    {{ $producto->descripcion }}
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">
                    <strong>Creado:</strong> 
                    @if($producto->created_at)
                        {{ $producto->created_at->format('d/m/Y H:i') }}
                    @else
                        Fecha no disponible
                    @endif
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">
                    <strong>Actualizado:</strong> 
                    @if($producto->updated_at)
                        {{ $producto->updated_at->format('d/m/Y H:i') }}
                    @else
                        Fecha no disponible
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>
@endsection