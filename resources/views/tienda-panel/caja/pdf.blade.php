<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cierre de Caja - {{ $cierre->fecha->format('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1565C0; }
        .header p { margin: 5px 0; color: #666; }
        .section { margin-bottom: 20px; }
        .section-title { background: #1565C0; color: white; padding: 8px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background: #e3f2fd; font-weight: bold; }
        .success { color: #2E7D32; }
        .danger { color: #C62828; }
        .info { color: #1565C0; }
        .summary-box { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 10px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $tienda->nombre }}</h1>
        <p>CIERRE DE CAJA</p>
        <p><strong>Fecha:</strong> {{ $cierre->fecha->format('d/m/Y') }} ({{ $cierre->fecha->dayName }})</p>
    </div>

    <div class="section">
        <div class="section-title">INFORMACIÓN DEL CIERRE</div>
        <table>
            <tr>
                <td><strong>Hora Apertura:</strong></td>
                <td>{{ $cierre->hora_apertura ? $cierre->hora_apertura->format('H:i') : '-' }}</td>
                <td><strong>Hora Cierre:</strong></td>
                <td>{{ $cierre->hora_cierre ? $cierre->hora_cierre->format('H:i') : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Estado:</strong></td>
                <td colspan="3">{{ ucfirst($cierre->estado) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">RESUMEN FINANCIERO</div>
        <div class="summary-box">
            <table>
                <tr>
                    <td width="50%"><strong>Monto Apertura:</strong></td>
                    <td class="text-right">{{ number_format($cierre->monto_apertura, 2) }} Bs</td>
                </tr>
                <tr>
                    <td><strong>Total Ventas del Día:</strong></td>
                    <td class="text-right info">{{ number_format($cierre->total_ventas, 2) }} Bs</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">VENTAS POR MÉTODO DE PAGO</div>
        <table>
            <tr>
                <th>Método</th>
                <th class="text-right">Monto</th>
            </tr>
            <tr>
                <td><i class="fas fa-money-bill"></i> Efectivo</td>
                <td class="text-right">{{ number_format($cierre->total_efectivo, 2) }} Bs</td>
            </tr>
            <tr>
                <td>Tarjeta</td>
                <td class="text-right">{{ number_format($cierre->total_tarjeta, 2) }} Bs</td>
            </tr>
            <tr>
                <td>QR</td>
                <td class="text-right">{{ number_format($cierre->total_qr, 2) }} Bs</td>
            </tr>
            <tr>
                <td>Transferencia</td>
                <td class="text-right">{{ number_format($cierre->total_transferencia, 2) }} Bs</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL</td>
                <td class="text-right">{{ number_format($cierre->total_ventas, 2) }} Bs</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">CUADRE DE CAJA (EFECTIVO)</div>
        <table>
            <tr>
                <td>Monto Apertura:</td>
                <td class="text-right">{{ number_format($cierre->monto_apertura, 2) }} Bs</td>
            </tr>
            <tr>
                <td>+ Ventas en Efectivo:</td>
                <td class="text-right">{{ number_format($cierre->total_efectivo, 2) }} Bs</td>
            </tr>
            <tr class="total-row">
                <td>= Efectivo Esperado:</td>
                <td class="text-right">{{ number_format($cierre->monto_apertura + $cierre->total_efectivo, 2) }} Bs</td>
            </tr>
            <tr>
                <td>Efectivo Contado:</td>
                <td class="text-right">{{ number_format($cierre->monto_contado ?? 0, 2) }} Bs</td>
            </tr>
            <tr class="total-row">
                <td>DIFERENCIA:</td>
                <td class="text-right {{ $cierre->diferencia >= 0 ? 'success' : 'danger' }}">
                    @if($cierre->diferencia == 0)
                        CUADRA
                    @elseif($cierre->diferencia > 0)
                        Sobrante: {{ number_format($cierre->diferencia, 2) }} Bs
                    @else
                        Faltante: {{ number_format(abs($cierre->diferencia), 2) }} Bs
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @if($pedidos->count() > 0)
    <div class="section">
        <div class="section-title">DETALLE DE PEDIDOS ({{ $pedidos->count() }})</div>
        <table>
            <tr>
                <th>ID</th>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Método Pago</th>
                <th class="text-right">Total</th>
            </tr>
            @foreach($pedidos as $pedido)
            <tr>
                <td>#{{ $pedido->id }}</td>
                <td>{{ $pedido->created_at->format('H:i') }}</td>
                <td>{{ $pedido->user->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($pedido->metodo_pago) }}</td>
                <td class="text-right">{{ number_format($pedido->total, 2) }} Bs</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    @if($cierre->observaciones)
    <div class="section">
        <div class="section-title">OBSERVACIONES</div>
        <p>{{ $cierre->observaciones }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i') }} | {{ $tienda->nombre }}</p>
    </div>
</body>
</html>
