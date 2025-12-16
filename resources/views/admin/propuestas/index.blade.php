@extends('admin.layouts.app')

@section('title', 'Propuestas de Productos')
@section('page-title', 'Propuestas de Productos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Propuestas</li>
@endsection

@section('content')
    @if($pendientesCount > 0)
        <div class="alert alert-warning">
            <i class="fas fa-bell mr-2"></i>
            Tienes <strong>{{ $pendientesCount }}</strong> propuesta(s) pendiente(s) de revisión.
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Propuestas de Tiendas
                @if(request('estado'))
                    <small class="text-muted">- Filtrado por: {{ ucfirst(request('estado')) }}</small>
                @endif
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.propuestas.index') }}" 
                       class="btn btn-sm {{ !request('estado') ? 'btn-primary' : 'btn-outline-primary' }}">
                        Pendientes
                    </a>
                    <a href="{{ route('admin.propuestas.index', ['estado' => 'aprobado']) }}" 
                       class="btn btn-sm {{ request('estado') == 'aprobado' ? 'btn-success' : 'btn-outline-success' }}">
                        Aprobados
                    </a>
                    <a href="{{ route('admin.propuestas.index', ['estado' => 'rechazado']) }}" 
                       class="btn btn-sm {{ request('estado') == 'rechazado' ? 'btn-danger' : 'btn-outline-danger' }}">
                        Rechazados
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            @if($propuestas->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4>No hay propuestas {{ request('estado') ? request('estado') . 's' : 'pendientes' }}</h4>
                    <p class="text-muted">Las tiendas pueden proponer nuevos productos para el catálogo.</p>
                </div>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Imagen</th>
                            <th>Producto</th>
                            <th>Propuesto por</th>
                            <th>Categoría</th>
                            <th>Precio Sugerido</th>
                            <th>Estado</th>
                            <th style="width: 180px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($propuestas as $propuesta)
                            <tr>
                                <td>
                                    @if($propuesta->imagen)
                                        <img src="{{ $propuesta->imagen_url }}" 
                                             class="img-fluid rounded" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $propuesta->nombre }}</strong>
                                    @if($propuesta->descripcion)
                                        <br><small class="text-muted">{{ Str::limit($propuesta->descripcion, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($propuesta->propuestoPorTienda)
                                        <i class="fas fa-store mr-1"></i>
                                        {{ $propuesta->propuestoPorTienda->nombre }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $propuesta->categoria_nombre }}</td>
                                <td>
                                    @if($propuesta->precio_sugerido)
                                        {{ number_format($propuesta->precio_sugerido, 2) }} Bs
                                    @else
                                        <span class="text-muted">No sugerido</span>
                                    @endif
                                </td>
                                <td>
                                    @if($propuesta->estado_aprobacion === 'pendiente')
                                        <span class="badge badge-warning">Pendiente</span>
                                    @elseif($propuesta->estado_aprobacion === 'aprobado')
                                        <span class="badge badge-success">Aprobado</span>
                                        <br><small>{{ $propuesta->precio_mayorista_formateado }}</small>
                                    @else
                                        <span class="badge badge-danger">Rechazado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($propuesta->estado_aprobacion === 'pendiente')
                                        <button type="button" class="btn btn-sm btn-success" 
                                                data-toggle="modal" 
                                                data-target="#aprobarModal{{ $propuesta->id }}">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-toggle="modal" 
                                                data-target="#rechazarModal{{ $propuesta->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.propuestas.show', $propuesta) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal Aprobar -->
                            <div class="modal fade" id="aprobarModal{{ $propuesta->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.propuestas.aprobar', $propuesta) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">Aprobar Producto</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Producto:</strong> {{ $propuesta->nombre }}</p>
                                                @if($propuesta->precio_sugerido)
                                                    <p><strong>Precio sugerido por tienda:</strong> {{ number_format($propuesta->precio_sugerido, 2) }} Bs</p>
                                                @endif
                                                <hr>
                                                <div class="form-group">
                                                    <label for="precio_mayorista{{ $propuesta->id }}">
                                                        Precio Mayorista (Bs) <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="number" step="0.01" name="precio_mayorista" 
                                                           id="precio_mayorista{{ $propuesta->id }}"
                                                           class="form-control" 
                                                           value="{{ $propuesta->precio_sugerido ?? '' }}"
                                                           min="0.01" required
                                                           placeholder="Define el precio mayorista">
                                                    <small class="text-muted">Este será el precio al que las tiendas compran el producto.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check mr-1"></i> Aprobar Producto
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Rechazar -->
                            <div class="modal fade" id="rechazarModal{{ $propuesta->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.propuestas.rechazar', $propuesta) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Rechazar Propuesta</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Producto:</strong> {{ $propuesta->nombre }}</p>
                                                <div class="form-group">
                                                    <label for="motivo_rechazo{{ $propuesta->id }}">
                                                        Motivo del Rechazo <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea name="motivo_rechazo" 
                                                              id="motivo_rechazo{{ $propuesta->id }}"
                                                              class="form-control" 
                                                              rows="3" required
                                                              placeholder="Indica por qué se rechaza esta propuesta..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-times mr-1"></i> Rechazar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if($propuestas->hasPages())
            <div class="card-footer">
                {{ $propuestas->links() }}
            </div>
        @endif
    </div>
@endsection
