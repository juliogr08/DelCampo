<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4A7C23;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #4A7C23;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .resumen {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .resumen h3 {
            margin: 0 0 10px;
            color: #4A7C23;
        }
        .resumen-grid {
            display: table;
            width: 100%;
        }
        .resumen-item {
            display: table-cell;
            text-align: center;
            padding: 10px;
        }
        .resumen-item .valor {
            font-size: 20px;
            font-weight: bold;
            color: #4A7C23;
        }
        .resumen-item .label {
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #4A7C23;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌽 Del Campo - Reporte de Ventas</h1>
        <p>Período: {{ $fechaDesde }} al {{ $fechaHasta }}</p>
        <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="resumen">
        <h3>Resumen Ejecutivo</h3>
        <table style="margin: 0;">
            <tr>
                <td style="border: none; text-align: center;">
                    <div style="font-size: 24px; font-weight: bold; color: #4A7C23;">{{ $resumen['total_pedidos'] }}</div>
                    <div style="font-size: 11px; color: #666;">Total Pedidos</div>
                </td>
                <td style="border: none; text-align: center;">
                    <div style="font-size: 24px; font-weight: bold; color: #4A7C23;">{{ number_format($resumen['total_ventas'], 2) }} Bs</div>
                    <div style="font-size: 11px; color: #666;">Total Ventas</div>
                </td>
                <td style="border: none; text-align: center;">
                    <div style="font-size: 24px; font-weight: bold; color: #4A7C23;">{{ number_format($resumen['promedio_pedido'], 2) }} Bs</div>
                    <div style="font-size: 11px; color: #666;">Promedio por Pedido</div>
                </td>
                <td style="border: none; text-align: center;">
                    <div style="font-size: 24px; font-weight: bold; color: #4A7C23;">{{ number_format($resumen['total_envios'], 2) }} Bs</div>
                    <div style="font-size: 11px; color: #666;">Total Envíos</div>
                </td>
            </tr>
        </table>
    </div>

    <h3>Detalle de Ventas por Día</h3>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th class="text-right">Cantidad de Pedidos</th>
                <th class="text-right">Total Ventas (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventasPorDia as $dia)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($dia->fecha)->format('d/m/Y') }}</td>
                    <td class="text-right">{{ $dia->cantidad }}</td>
                    <td class="text-right">{{ number_format($dia->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No hay ventas en este período</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Del Campo - Sistema E-Commerce | Reporte generado automáticamente
    </div>
</body>
</html>
