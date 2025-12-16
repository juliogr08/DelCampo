@extends('admin.layouts.app')

@section('title', 'Propuesta: ' . $producto->nombre)
@section('page-title', 'Detalle de Propuesta')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.propuestas.index') }}">Propuestas</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($producto->nombre, 30) }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $producto->nombre }}</h3>
                    <div class="card-tools">
                        @if($producto->estado_aprobacion === 'pendiente')
                            <span class="badge badge-warning">Pendiente</span>
                        @elseif($producto->estado_aprobacion === 'aprobado')
                            <span class="badge badge-success">Aprobado</span>
                        @else
                            <span class="badge badge-danger">Rechazado</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($producto->imagen)
                                <img src="{{ $producto->imagen_url }}" class="img-fluid rounded" alt="{{ $producto->nombre }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-sm">
                                <tr>
                                    <th>Categoría:</th>
                                    <td>{{ $producto->categoria_nombre }}</td>
                                </tr>
                                <tr>
                                    <th>Unidad:</th>
                                    <td>{{ $producto->unidad_medida_nombre }}</td>
                                </tr>
                                <tr>
                                    <th>Precio Sugerido:</th>
                                    <td>
                                        @if($producto->precio_sugerido)
                                            {{ number_format($producto->precio_sugerido, 2) }} Bs
                                        @else
                                            <span class="text-muted">No sugerido</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($producto->estado_aprobacion === 'aprobado')
                                    <tr>
                                        <th>Precio Mayorista:</th>
                                        <td class="text-success font-weight-bold">{{ $producto->precio_mayorista_formateado }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Propuesto por:</th>
                                    <td>
                                        @if($producto->propuestoPorTienda)
                                            <i class="fas fa-store mr-1"></i>
                                            {{ $producto->propuestoPorTienda->nombre }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha:</th>
                                    <td>{{ $producto->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($producto->descripcion)
                        <hr>
                        <h6>Descripción:</h6>
                        <p>{{ $producto->descripcion }}</p>
                    @endif

                    @if($producto->estado_aprobacion === 'rechazado' && $producto->motivo_rechazo)
                        <hr>
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-times-circle mr-1"></i> Motivo del Rechazo:</h6>
                            <p class="mb-0">{{ $producto->motivo_rechazo }}</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.propuestas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
