@extends('tienda-panel.layouts.app')

@section('title', 'Catálogo del Administrador - ' . $tienda->nombre)
@section('page-title', 'Catálogo del Administrador')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Catálogo Admin</li>
@endsection

@section('content')
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-2"></i>
        <strong>¿Cómo funciona?</strong> Aquí puedes ver los productos disponibles del administrador. 
        Adopta un producto, define tu precio de venta y luego solicita stock para comenzar a vender.
    </div>

    @if($productosMaestros->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4>No hay productos disponibles</h4>
                <p class="text-muted">El administrador aún no ha agregado productos al catálogo.</p>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($productosMaestros as $producto)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 {{ in_array($producto->id, $productosAdoptados) ? 'border-success' : '' }}">
                        @if($producto->imagen)
                            <img src="{{ $producto->imagen_url }}" class="card-img-top" alt="{{ $producto->nombre }}" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <span class="badge badge-secondary mb-2">{{ $producto->categoria_nombre }}</span>
                            @if(in_array($producto->id, $productosAdoptados))
                                <span class="badge badge-success mb-2">Ya lo tienes</span>
                            @endif
                            
                            <h5 class="card-title">{{ $producto->nombre }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($producto->descripcion, 80) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Precio Mayorista:</small><br>
                                    <strong class="text-primary">{{ $producto->precio_mayorista_formateado }}</strong>
                                </div>
                                <small class="text-muted">{{ $producto->unidad_medida_nombre }}</small>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            @if(in_array($producto->id, $productosAdoptados))
                                <button class="btn btn-success btn-block" disabled>
                                    <i class="fas fa-check mr-1"></i> Ya está en tu tienda
                                </button>
                            @else
                                <button type="button" class="btn btn-primary btn-block" 
                                        data-toggle="modal" 
                                        data-target="#adoptarModal{{ $producto->id }}">
                                    <i class="fas fa-plus-circle mr-1"></i> Agregar a mi tienda
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Adoptar -->
                @if(!in_array($producto->id, $productosAdoptados))
                <div class="modal fade" id="adoptarModal{{ $producto->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('tienda.panel.catalogo-admin.adoptar', $producto) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Agregar "{{ $producto->nombre }}" a tu tienda</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Precio mayorista: <strong>{{ $producto->precio_mayorista_formateado }}</strong>
                                        <br>
                                        <small>Tu precio de venta debe ser mayor para obtener ganancia.</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="precio{{ $producto->id }}">
                                            Tu Precio de Venta (Bs) <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" step="0.01" name="precio" 
                                               id="precio{{ $producto->id }}"
                                               class="form-control" 
                                               min="{{ $producto->precio_mayorista ?? 0 }}"
                                               placeholder="Ej: {{ number_format(($producto->precio_mayorista ?? 0) * 1.3, 2) }}"
                                               required>
                                        <small class="text-muted">
                                            Sugerencia: {{ number_format(($producto->precio_mayorista ?? 0) * 1.3, 2) }} Bs (30% ganancia)
                                        </small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus-circle mr-1"></i> Agregar a mi tienda
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $productosMaestros->links() }}
        </div>
    @endif
@endsection
