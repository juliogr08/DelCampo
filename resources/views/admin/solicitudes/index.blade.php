@extends('admin.layouts.app')

@section('title', $tipoActual == 'tienda_a_admin' ? 'Pedidos de Tiendas' : 'Mis Solicitudes')
@section('page-title', $tipoActual == 'tienda_a_admin' ? 'Pedidos de Tiendas' : 'Mis Solicitudes de Stock')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">{{ $tipoActual == 'tienda_a_admin' ? 'Pedidos de Tiendas' : 'Mis Solicitudes' }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                @if($tipoActual == 'tienda_a_admin')
                    <i class="fas fa-inbox mr-2"></i>Pedidos de Stock de Tiendas
                @else
                    <i class="fas fa-truck-loading mr-2"></i>Mis Solicitudes a Distribuidores
                @endif
            </h3>
            @if($tipoActual != 'tienda_a_admin')
                <a href="{{ route('admin.solicitudes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Nueva Solicitud
                </a>
            @endif
        </div>
        <div class="card-body">
            <!-- Filtro -->
            <form action="{{ route('admin.solicitudes.index') }}" method="GET" class="mb-4">
                <input type="hidden" name="tipo" value="{{ $tipoActual }}">
                <div class="row">
                    <div class="col-md-3">
                        <select name="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            @foreach(App\Models\SolicitudReposicion::ESTADOS as $key => $value)
                                <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            @if($tipoActual == 'tienda_a_admin')
                                <th>Tienda</th>
                            @else
                                <th>Almacén Destino</th>
                            @endif
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $solicitud)
                            <tr>
                                <td><strong>#{{ $solicitud->id }}</strong></td>
                                <td>
                                    @if($tipoActual == 'tienda_a_admin')
                                        @if($solicitud->tiendaSolicitante)
                                            <strong>{{ $solicitud->tiendaSolicitante->nombre }}</strong>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    @else
                                        @if($solicitud->almacen)
                                            {{ $solicitud->almacen->nombre_almacen }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    {{ $solicitud->producto->nombre ?? 'N/A' }}
                                    @if($solicitud->producto)
                                        <br>
                                        <small class="text-muted">Stock: {{ $solicitud->producto->stock }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $solicitud->cantidad_solicitada }}</strong>
                                    @if($solicitud->cantidad_recibida)
                                        <br>
                                        <small class="text-success">
                                            {{ $tipoActual == 'tienda_a_admin' ? 'Enviado' : 'Recibido' }}: {{ $solicitud->cantidad_recibida }}
                                        </small>
                                    @endif
                                </td>
                                <td><strong>{{ number_format($solicitud->monto_total ?? 0, 2) }} Bs</strong></td>
                                <td>{!! $solicitud->estado_badge !!}</td>
                                <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($tipoActual == 'tienda_a_admin')
                                        {{-- Acciones para pedidos de tiendas --}}
                                        @if($solicitud->estado == 'pendiente')
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    data-toggle="modal" data-target="#aprobarModal{{ $solicitud->id }}">
                                                <i class="fas fa-check"></i> Aprobar
                                            </button>
                                        @else
                                            <span class="text-muted">Procesado</span>
                                        @endif
                                    @else
                                        {{-- Acciones para mis solicitudes a distribuidores --}}
                                        @if($solicitud->estado == 'pendiente')
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    data-toggle="modal" data-target="#recibirModal{{ $solicitud->id }}">
                                                <i class="fas fa-check"></i> Recibir
                                            </button>
                                            <form action="{{ route('admin.solicitudes.destroy', $solicitud) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('¿Eliminar esta solicitud?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">Procesada</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>

                            @if($tipoActual == 'tienda_a_admin' && $solicitud->estado == 'pendiente')
                                <!-- Modal Aprobar para tiendas -->
                                <div class="modal fade" id="aprobarModal{{ $solicitud->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.solicitudes.recibir', $solicitud) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title">Aprobar y Enviar Stock</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Tienda:</strong> {{ $solicitud->tiendaSolicitante->nombre ?? 'N/A' }}</p>
                                                    <p><strong>Producto:</strong> {{ $solicitud->producto->nombre ?? 'N/A' }}</p>
                                                    <p><strong>Cantidad Solicitada:</strong> {{ $solicitud->cantidad_solicitada }}</p>
                                                    <div class="form-group">
                                                        <label for="cantidad_recibida">Cantidad a Enviar:</label>
                                                        <input type="number" name="cantidad_recibida" class="form-control" 
                                                               value="{{ $solicitud->cantidad_solicitada }}" 
                                                               min="1" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-paper-plane mr-1"></i> Aprobar y Enviar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @elseif($tipoActual != 'tienda_a_admin' && $solicitud->estado == 'pendiente')
                                <!-- Modal Recibir para mis solicitudes -->
                                <div class="modal fade" id="recibirModal{{ $solicitud->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.solicitudes.recibir', $solicitud) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Recibir Productos</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Producto:</strong> {{ $solicitud->producto->nombre ?? 'N/A' }}</p>
                                                    <p><strong>Cantidad Solicitada:</strong> {{ $solicitud->cantidad_solicitada }}</p>
                                                    <div class="form-group">
                                                        <label for="cantidad_recibida">Cantidad Recibida:</label>
                                                        <input type="number" name="cantidad_recibida" class="form-control" 
                                                               value="{{ $solicitud->cantidad_solicitada }}" 
                                                               min="1" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check mr-1"></i> Confirmar Recepción
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                    @if($tipoActual == 'tienda_a_admin')
                                        <p class="text-muted mb-0">No hay pedidos de tiendas</p>
                                    @else
                                        <p class="text-muted mb-0">No hay solicitudes registradas</p>
                                        <a href="{{ route('admin.solicitudes.create') }}" class="btn btn-primary mt-3">
                                            <i class="fas fa-plus"></i> Crear primera solicitud
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($solicitudes->hasPages())
                <div class="mt-3">
                    {{ $solicitudes->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
