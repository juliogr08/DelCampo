@extends('admin.layouts.app')

@section('title', 'Cliente {{ $cliente->name }} - Admin')
@section('page-title', 'Detalle del Cliente')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.clientes.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">{{ $cliente->name }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr($cliente->name, 0, 1)) }}
                    </div>
                    <h4>{{ $cliente->name }}</h4>
                    <p class="text-muted">{{ $cliente->email }}</p>
                    
                    <hr>
                    
                    <table class="table table-sm table-borderless text-left">
                        <tr>
                            <td class="text-muted"><i class="fas fa-phone mr-2"></i>Teléfono:</td>
                            <td>{{ $cliente->telefono ?? 'No registrado' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fas fa-map-marker-alt mr-2"></i>Ciudad:</td>
                            <td>{{ $cliente->ciudad ?? 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fas fa-home mr-2"></i>Dirección:</td>
                            <td>{{ $cliente->direccion ?? 'No registrada' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fas fa-calendar mr-2"></i>Registro:</td>
                            <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Estadísticas</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Pedidos:</span>
                        <strong>{{ $estadisticas['total_pedidos'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total Gastado:</span>
                        <strong class="text-success">{{ number_format($estadisticas['total_gastado'], 2) }} Bs</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-shopping-basket mr-2"></i>Últimos Pedidos</h3>
                </div>
                <div class="card-body">
                    @if($cliente->pedidos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cliente->pedidos as $pedido)
                                        <tr>
                                            <td><strong>{{ $pedido->codigo }}</strong></td>
                                            <td>{{ number_format($pedido->total, 2) }} Bs</td>
                                            <td>{!! $pedido->estado_badge !!}</td>
                                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.pedidos.show', $pedido) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Este cliente no tiene pedidos aún</p>
                        </div>
                    @endif
                </div>
            </div>

            <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Volver a Clientes
            </a>
        </div>
    </div>
@endsection
