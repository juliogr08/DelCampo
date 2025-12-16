@extends('tienda-panel.layouts.app')

@section('title', 'Nueva Solicitud - ' . $tienda->nombre)
@section('page-title', 'Nueva Solicitud de Stock')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.solicitudes.index') }}">Solicitudes</a></li>
    <li class="breadcrumb-item active">Nueva</li>
@endsection

@section('content')
    @if($tienda->estado === 'pendiente')
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>¡Tu primera solicitud!</strong> Al completar esta solicitud, tu tienda será activada automáticamente.
        </div>
    @endif

    <div class="card">
        <form action="{{ route('tienda.panel.solicitudes.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="producto_id">Producto a Solicitar <span class="text-danger">*</span></label>
                            <select name="producto_id" id="producto_id" 
                                    class="form-control @error('producto_id') is-invalid @enderror" required>
                                <option value="">Seleccionar producto...</option>
                                @foreach($productosAdmin as $producto)
                                    <option value="{{ $producto->id }}" 
                                            data-precio="{{ $producto->precio_mayorista ?? $producto->precio }}"
                                            {{ (old('producto_id', $productoIdSeleccionado ?? '') == $producto->id) ? 'selected' : '' }}>
                                        {{ $producto->nombre }} 
                                        - {{ number_format($producto->precio_mayorista ?? $producto->precio, 2) }} Bs/{{ $producto->unidad_medida }}
                                        @if($producto->tienda_id) (Tu producto) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('producto_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cantidad_solicitada">Cantidad <span class="text-danger">*</span></label>
                            <input type="number" name="cantidad_solicitada" id="cantidad_solicitada" 
                                   class="form-control @error('cantidad_solicitada') is-invalid @enderror"
                                   value="{{ old('cantidad_solicitada', 1) }}" required min="1" onchange="calcularTotal()">
                            @error('cantidad_solicitada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notas">Notas Adicionales</label>
                            <textarea name="notas" id="notas" rows="3"
                                      class="form-control @error('notas') is-invalid @enderror"
                                      placeholder="Instrucciones especiales, urgencia, etc.">{{ old('notas') }}</textarea>
                            @error('notas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5>Resumen de Solicitud</h5>
                                <hr>
                                <p><strong>Precio unitario:</strong> <span id="precioUnitario">0.00</span> Bs</p>
                                <p><strong>Cantidad:</strong> <span id="cantidadResumen">0</span></p>
                                <hr>
                                <p class="h4"><strong>Total:</strong> <span id="totalEstimado">0.00</span> Bs</p>
                                <small class="text-muted">* Este monto deberá ser coordinado con el administrador</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-1"></i> Enviar Solicitud
                </button>
                <a href="{{ route('tienda.panel.solicitudes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('producto_id').addEventListener('change', calcularTotal);
document.getElementById('cantidad_solicitada').addEventListener('input', calcularTotal);

function calcularTotal() {
    const select = document.getElementById('producto_id');
    const cantidad = parseInt(document.getElementById('cantidad_solicitada').value) || 0;
    const option = select.options[select.selectedIndex];
    const precio = parseFloat(option.dataset.precio) || 0;
    
    document.getElementById('precioUnitario').textContent = precio.toFixed(2);
    document.getElementById('cantidadResumen').textContent = cantidad;
    document.getElementById('totalEstimado').textContent = (precio * cantidad).toFixed(2);
}

calcularTotal();
</script>
@endpush
