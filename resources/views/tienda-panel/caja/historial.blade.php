@extends('tienda-panel.layouts.app')

@section('title', 'Historial de Caja - ' . $tienda->nombre)
@section('page-title', 'Historial de Cierres')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tienda.panel.caja') }}">Caja</a></li>
    <li class="breadcrumb-item active">Historial</li>
@endsection

@section('content')
    <!-- Estadísticas del Mes -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($estadisticasMes->total_ventas ?? 0, 2) }}</h3>
                    <p>Total Ventas (Bs)</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticasMes->dias_trabajados ?? 0 }}</h3>
                    <p>Días Trabajados</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($estadisticasMes->total_efectivo ?? 0, 2) }}</h3>
                    <p>Efectivo (Bs)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-{{ ($estadisticasMes->total_diferencias ?? 0) >= 0 ? 'info' : 'danger' }}">
                <div class="inner">
                    <h3>{{ number_format($estadisticasMes->total_diferencias ?? 0, 2) }}</h3>
                    <p>Diferencias (Bs)</p>
                </div>
                <div class="icon"><i class="fas fa-balance-scale"></i></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-history mr-2"></i>Historial de Cierres</h3>
            <div class="card-tools">
                <form method="GET" action="{{ route('tienda.panel.caja.historial') }}" class="form-inline">
                    <input type="month" name="mes" class="form-control form-control-sm" 
                           value="{{ $mesActual }}" onchange="this.form.submit()">
                </form>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            @if($cierres->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No hay cierres registrados</p>
                    <a href="{{ route('tienda.panel.caja') }}" class="btn btn-primary">
                        <i class="fas fa-cash-register mr-1"></i> Ir a Caja
                    </a>
                </div>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Apertura</th>
                            <th>Total Ventas</th>
                            <th>Efectivo</th>
                            <th>Otros</th>
                            <th>Contado</th>
                            <th>Diferencia</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cierres as $cierre)
                            <tr>
                                <td>
                                    <strong>{{ $cierre->fecha->format('d/m/Y') }}</strong>
                                    <br><small class="text-muted">{{ $cierre->fecha->dayName }}</small>
                                </td>
                                <td>{{ number_format($cierre->monto_apertura, 2) }} Bs</td>
                                <td>
                                    <strong>{{ number_format($cierre->total_ventas, 2) }} Bs</strong>
                                </td>
                                <td>{{ number_format($cierre->total_efectivo, 2) }} Bs</td>
                                <td>
                                    @php
                                        $otros = $cierre->total_tarjeta + $cierre->total_qr + $cierre->total_transferencia;
                                    @endphp
                                    {{ number_format($otros, 2) }} Bs
                                </td>
                                <td>{{ $cierre->monto_contado ? number_format($cierre->monto_contado, 2) . ' Bs' : '-' }}</td>
                                <td>{!! $cierre->diferencia_badge !!}</td>
                                <td>{!! $cierre->estado_badge !!}</td>
                                <td>
                                    @if($cierre->esta_cerrada)
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('tienda.panel.caja.pdf', $cierre) }}" 
                                               class="btn btn-outline-danger" title="PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="{{ route('tienda.panel.caja.excel', $cierre) }}" 
                                               class="btn btn-outline-success" title="Excel">
                                                <i class="fas fa-file-excel"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-muted">Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if($cierres->hasPages())
            <div class="card-footer">
                {{ $cierres->appends(['mes' => $mesActual])->links() }}
            </div>
        @endif
    </div>
@endsection
