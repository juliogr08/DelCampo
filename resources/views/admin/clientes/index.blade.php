@extends('admin.layouts.app')

@section('title', 'Clientes - Admin')
@section('page-title', 'Clientes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Clientes</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users mr-2"></i>Lista de Clientes</h3>
        </div>
        <div class="card-body">
            <!-- Búsqueda -->
            <form action="{{ route('admin.clientes.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="buscar" class="form-control" 
                                   placeholder="Buscar por nombre, email o teléfono..." 
                                   value="{{ request('buscar') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(request('buscar'))
                        <div class="col-md-2">
                            <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-secondary">
                                Limpiar
                            </a>
                        </div>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>Ciudad</th>
                            <th>Pedidos</th>
                            <th>Registro</th>
                            <th width="100">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-2" 
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($cliente->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $cliente->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $cliente->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($cliente->telefono)
                                        <i class="fas fa-phone text-muted"></i> {{ $cliente->telefono }}
                                    @else
                                        <span class="text-muted">Sin teléfono</span>
                                    @endif
                                </td>
                                <td>{{ $cliente->ciudad ?? 'No especificada' }}</td>
                                <td>
                                    <span class="badge badge-primary badge-pill">
                                        {{ $cliente->pedidos_count ?? 0 }} pedidos
                                    </span>
                                </td>
                                <td>
                                    {{ $cliente->created_at->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">{{ $cliente->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.clientes.show', $cliente) }}" 
                                       class="btn btn-sm btn-primary" title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">No hay clientes registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($clientes->hasPages())
                <div class="mt-3">
                    {{ $clientes->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
