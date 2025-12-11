@extends('admin.layouts.app')

@section('title', 'Pedido {{ $pedido->codigo }} - Admin')
@section('page-title', 'Detalle del Pedido')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pedidos.index') }}">Pedidos</a></li>
    <li class="breadcrumb-item active">{{ $pedido->codigo }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <!-- Info del pedido -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-receipt mr-2"></i>Pedido #{{ $pedido->codigo }}
                    </h3>
                    {!! $pedido->estado_badge !!}
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">CLIENTE</h6>
                            <p class="mb-1"><strong>{{ $pedido->user->name }}</strong></p>
                            <p class="mb-1 text-muted">{{ $pedido->user->email }}</p>
                            <p class="mb-0 text-muted">{{ $pedido->user->telefono ?? 'Sin teléfono' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">ENTREGA</h6>
                            <p class="mb-1">{{ $pedido->direccion_entrega }}</p>
                            <p class="mb-1 text-muted">
                                <i class="fas fa-warehouse"></i> Desde: {{ $pedido->almacen->nombre_almacen }}
                            </p>
                            <p class="mb-0 text-muted">
                                <i class="fas fa-route"></i> Distancia: {{ $pedido->distancia_km }} km
                            </p>
                        </div>
                    </div>

                    <h6 class="text-muted mb-3">PRODUCTOS</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-right">Precio</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->detalles as $detalle)
                                    <tr>
                                        <td>{{ $detalle->producto->nombre }}</td>
                                        <td class="text-center">{{ $detalle->cantidad }}</td>
                                        <td class="text-right">{{ number_format($detalle->precio_unitario, 2) }} Bs</td>
                                        <td class="text-right">{{ number_format($detalle->subtotal, 2) }} Bs</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                    <td class="text-right">{{ number_format($pedido->subtotal, 2) }} Bs</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Costo de Envío:</strong></td>
                                    <td class="text-right">{{ number_format($pedido->costo_envio, 2) }} Bs</td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                    <td class="text-right"><strong>{{ number_format($pedido->total, 2) }} Bs</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($pedido->observaciones)
                        <div class="alert alert-light mt-3">
                            <strong><i class="fas fa-comment-alt mr-1"></i> Observaciones:</strong>
                            <p class="mb-0 mt-1">{{ $pedido->observaciones }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Cambiar estado -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-exchange-alt mr-2"></i>Cambiar Estado</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pedidos.estado', $pedido) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label>Estado Actual:</label>
                            <div class="mb-3">{!! $pedido->estado_badge !!}</div>
                        </div>
                        <div class="form-group">
                            <label for="estado">Nuevo Estado:</label>
                            <select name="estado" id="estado" class="form-control" required>
                                @foreach(App\Models\Pedido::ESTADOS as $key => $value)
                                    <option value="{{ $key }}" {{ $pedido->estado == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-check mr-1"></i> Actualizar Estado
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info adicional -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Información</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">Método de pago:</td>
                            <td><strong>{{ $pedido->metodo_pago_nombre }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fecha:</td>
                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Última actualización:</td>
                            <td>{{ $pedido->updated_at->diffForHumans() }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline-secondary btn-block">
                <i class="fas fa-arrow-left mr-1"></i> Volver a Pedidos
            </a>
        </div>
    </div>
@endsection
