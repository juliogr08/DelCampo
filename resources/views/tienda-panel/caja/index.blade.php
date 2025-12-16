@extends('tienda-panel.layouts.app')

@section('title', 'Caja - ' . $tienda->nombre)
@section('page-title', 'Caja del Día')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Caja</li>
@endsection

@section('content')
    <div class="row">
        <!-- Estado de Caja -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">
                        <i class="fas fa-cash-register mr-2"></i>Estado de Caja
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('tienda.panel.caja.historial') }}" class="btn btn-tool text-white">
                            <i class="fas fa-history"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body text-center">
                    <h5>{{ $hoy->format('d/m/Y') }}</h5>
                    
                    @if(!$cajaHoy)
                        <!-- No hay caja abierta -->
                        <div class="my-4">
                            <i class="fas fa-lock fa-4x text-muted mb-3"></i>
                            <p class="text-muted">La caja no está abierta</p>
                        </div>
                        <button type="button" class="btn btn-success btn-lg btn-block" 
                                data-toggle="modal" data-target="#modalApertura">
                            <i class="fas fa-unlock mr-2"></i>Abrir Caja
                        </button>
                    @elseif($cajaHoy->esta_abierta)
                        <!-- Caja abierta -->
                        <div class="my-3">
                            <i class="fas fa-unlock fa-3x text-success mb-3"></i>
                            <p><span class="badge badge-success">ABIERTA</span></p>
                            <small class="text-muted">
                                Desde: {{ $cajaHoy->hora_apertura->format('H:i') }}
                            </small>
                        </div>
                        <hr>
                        <p><strong>Monto Apertura:</strong></p>
                        <h3 class="text-primary">{{ number_format($cajaHoy->monto_apertura, 2) }} Bs</h3>
                        <hr>
                        <button type="button" class="btn btn-danger btn-lg btn-block" 
                                data-toggle="modal" data-target="#modalCierre">
                            <i class="fas fa-lock mr-2"></i>Cerrar Caja
                        </button>
                    @else
                        <!-- Caja cerrada -->
                        <div class="my-3">
                            <i class="fas fa-lock fa-3x text-secondary mb-3"></i>
                            <p><span class="badge badge-secondary">CERRADA</span></p>
                            <small class="text-muted">
                                Cerrada: {{ $cajaHoy->hora_cierre->format('H:i') }}
                            </small>
                        </div>
                        <hr>
                        {!! $cajaHoy->diferencia_badge !!}
                        <hr>
                        <div class="btn-group btn-block">
                            <a href="{{ route('tienda.panel.caja.pdf', $cajaHoy) }}" class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a href="{{ route('tienda.panel.caja.excel', $cajaHoy) }}" class="btn btn-outline-success">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ventas del Día -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Ventas del Día</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Ventas</span>
                                    <span class="info-box-number">{{ number_format($ventasHoy['total'], 2) }} Bs</span>
                                    <span class="progress-description">{{ $ventasHoy['cantidad'] }} pedidos</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Efectivo</span>
                                    <span class="info-box-number">{{ number_format($ventasHoy['efectivo'], 2) }} Bs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-credit-card"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tarjeta</span>
                                    <span class="info-box-number">{{ number_format($ventasHoy['tarjeta'], 2) }} Bs</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-purple"><i class="fas fa-qrcode"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">QR</span>
                                    <span class="info-box-number">{{ number_format($ventasHoy['qr'], 2) }} Bs</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-teal"><i class="fas fa-university"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Transferencia</span>
                                    <span class="info-box-number">{{ number_format($ventasHoy['transferencia'], 2) }} Bs</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($cajaHoy && $cajaHoy->esta_abierta)
                        <div class="alert alert-info">
                            <i class="fas fa-calculator mr-2"></i>
                            <strong>Efectivo esperado al cierre:</strong> 
                            {{ number_format($cajaHoy->monto_apertura + $ventasHoy['efectivo'], 2) }} Bs
                            <small>(Apertura + Ventas Efectivo)</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Apertura -->
    <div class="modal fade" id="modalApertura" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('tienda.panel.caja.apertura') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-unlock mr-2"></i>Abrir Caja</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Ingresa el monto de efectivo con el que inicias el día:</p>
                        <div class="form-group">
                            <label for="monto_apertura">Monto de Apertura (Bs) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <input type="number" step="0.01" name="monto_apertura" id="monto_apertura" 
                                       class="form-control" value="0" min="0" required autofocus>
                                <div class="input-group-append">
                                    <span class="input-group-text">Bs</span>
                                </div>
                            </div>
                            <small class="text-muted">Puede ser 0 si empiezas sin efectivo</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-unlock mr-1"></i>Abrir Caja
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cierre -->
    @if($cajaHoy && $cajaHoy->esta_abierta)
    <div class="modal fade" id="modalCierre" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('tienda.panel.caja.cierre') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fas fa-lock mr-2"></i>Cerrar Caja</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Resumen del Día</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td>Monto Apertura:</td>
                                        <td class="text-right">{{ number_format($cajaHoy->monto_apertura, 2) }} Bs</td>
                                    </tr>
                                    <tr>
                                        <td>Ventas Efectivo:</td>
                                        <td class="text-right">+ {{ number_format($ventasHoy['efectivo'], 2) }} Bs</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><strong>Efectivo Esperado:</strong></td>
                                        <td class="text-right"><strong>{{ number_format($cajaHoy->monto_apertura + $ventasHoy['efectivo'], 2) }} Bs</strong></td>
                                    </tr>
                                </table>
                                
                                <h6 class="mt-4">Ventas por Método</h6>
                                <table class="table table-sm">
                                    <tr><td>Efectivo:</td><td class="text-right">{{ number_format($ventasHoy['efectivo'], 2) }} Bs</td></tr>
                                    <tr><td>Tarjeta:</td><td class="text-right">{{ number_format($ventasHoy['tarjeta'], 2) }} Bs</td></tr>
                                    <tr><td>QR:</td><td class="text-right">{{ number_format($ventasHoy['qr'], 2) }} Bs</td></tr>
                                    <tr><td>Transferencia:</td><td class="text-right">{{ number_format($ventasHoy['transferencia'], 2) }} Bs</td></tr>
                                    <tr class="table-success"><td><strong>Total:</strong></td><td class="text-right"><strong>{{ number_format($ventasHoy['total'], 2) }} Bs</strong></td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Conteo de Efectivo</h6>
                                <div class="form-group">
                                    <label for="monto_contado">¿Cuánto efectivo hay en caja? <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" step="0.01" name="monto_contado" id="monto_contado" 
                                               class="form-control" min="0" required
                                               onchange="calcularDiferencia()"
                                               value="{{ $cajaHoy->monto_apertura + $ventasHoy['efectivo'] }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="diferenciAlert" class="alert alert-success">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <strong>La caja cuadra</strong>
                                </div>
                                
                                <div class="form-group mt-3">
                                    <label for="observaciones">Observaciones (opcional)</label>
                                    <textarea name="observaciones" id="observaciones" rows="3" 
                                              class="form-control" placeholder="Notas sobre el cierre..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-lock mr-1"></i>Cerrar Caja
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
const esperado = {{ $cajaHoy ? $cajaHoy->monto_apertura + $ventasHoy['efectivo'] : 0 }};

function calcularDiferencia() {
    const contado = parseFloat(document.getElementById('monto_contado').value) || 0;
    const diferencia = contado - esperado;
    const alert = document.getElementById('diferenciAlert');
    
    if (diferencia === 0) {
        alert.className = 'alert alert-success';
        alert.innerHTML = '<i class="fas fa-check-circle mr-2"></i><strong>La caja cuadra</strong>';
    } else if (diferencia > 0) {
        alert.className = 'alert alert-info';
        alert.innerHTML = '<i class="fas fa-arrow-up mr-2"></i><strong>Sobrante:</strong> ' + diferencia.toFixed(2) + ' Bs';
    } else {
        alert.className = 'alert alert-danger';
        alert.innerHTML = '<i class="fas fa-arrow-down mr-2"></i><strong>Faltante:</strong> ' + Math.abs(diferencia).toFixed(2) + ' Bs';
    }
}
</script>
@endpush
