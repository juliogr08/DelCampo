@extends('tienda-panel.layouts.app')

@section('title', 'Mis Almacenes - ' . $tienda->nombre)
@section('page-title', 'Mis Almacenes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Almacenes</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Almacenes</h3>
            <div class="card-tools">
                <a href="{{ route('tienda.panel.almacenes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nuevo Almacén
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($almacenes->count() > 0)
                <div class="row">
                    @foreach($almacenes as $almacen)
                        <div class="col-md-6 mb-4">
                            <div class="card {{ $almacen->es_sede_principal ? 'border-primary' : '' }}">
                                <div class="card-header {{ $almacen->es_sede_principal ? 'bg-primary text-white' : 'bg-light' }}">
                                    <h5 class="mb-0">
                                        @if($almacen->es_sede_principal)
                                            <i class="fas fa-star mr-1"></i>
                                        @endif
                                        {{ $almacen->nombre_almacen }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                        {{ Str::limit($almacen->ubicacion, 50) }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-thermometer-half text-info mr-2"></i>
                                        {{ $almacen->tipo_almacenamiento_nombre }}
                                    </p>
                                    @if($almacen->responsable)
                                        <p class="mb-2">
                                            <i class="fas fa-user text-success mr-2"></i>
                                            {{ $almacen->responsable }}
                                        </p>
                                    @endif
                                    <p class="mb-0">
                                        {!! $almacen->estado_badge !!}
                                        @if($almacen->es_sede_principal)
                                            <span class="badge badge-primary ml-2">Sede Principal</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('tienda.panel.almacenes.edit', $almacen) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    @if(!$almacen->es_sede_principal)
                                        <form action="{{ route('tienda.panel.almacenes.destroy', $almacen) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Eliminar este almacén?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-warehouse fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No tienes almacenes adicionales</p>
                    <a href="{{ route('tienda.panel.almacenes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Agregar almacén
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
