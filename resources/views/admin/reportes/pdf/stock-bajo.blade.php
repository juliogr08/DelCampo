<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Stock Bajo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #DC3545;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #DC3545;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .alerta {
            background: #FFF3CD;
            border: 1px solid #FFECB5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alerta h3 {
            margin: 0 0 10px;
            color: #856404;
        }
        .resumen-table {
            width: 100%;
        }
        .resumen-table td {
            text-align: center;
            padding: 10px;
            border: none;
        }
        .resumen-valor {
            font-size: 28px;
            font-weight: bold;
        }
        .resumen-label {
            font-size: 11px;
            color: #666;
        }
        .danger { color: #DC3545; }
        .warning { color: #FFC107; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #DC3545;
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
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-danger {
            background: #DC3545;
            color: white;
        }
        .badge-warning {
            background: #FFC107;
            color: #212529;
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
        <h1>⚠️ Del Campo - Reporte de Stock Bajo</h1>
        <p>Generado: {{ $fecha ?? now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="alerta">
        <h3>⚠️ Atención: Productos que requieren reposición</h3>
        <table class="resumen-table">
            <tr>
                <td>
                    <div class="resumen-valor danger">{{ $resumen['total_productos'] }}</div>
                    <div class="resumen-label">Total Productos</div>
                </td>
                <td>
                    <div class="resumen-valor danger">{{ $resumen['agotados'] }}</div>
                    <div class="resumen-label">Agotados (0 unidades)</div>
                </td>
                <td>
                    <div class="resumen-valor warning">{{ $resumen['criticos'] }}</div>
                    <div class="resumen-label">Stock Crítico</div>
                </td>
            </tr>
        </table>
    </div>

    <h3>Detalle de Productos con Stock Bajo</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th class="text-right">Stock Actual</th>
                <th class="text-right">Stock Mínimo</th>
                <th class="text-right">Déficit</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $producto)
                @php
                    $deficit = $producto->stock_minimo - $producto->stock;
                @endphp
                <tr>
                    <td><strong>{{ $producto->nombre }}</strong></td>
                    <td>{{ App\Models\Producto::CATEGORIAS[$producto->categoria] ?? $producto->categoria }}</td>
                    <td class="text-right">{{ $producto->stock }}</td>
                    <td class="text-right">{{ $producto->stock_minimo }}</td>
                    <td class="text-right" style="color: #DC3545; font-weight: bold;">-{{ $deficit }}</td>
                    <td class="text-center">
                        @if($producto->stock == 0)
                            <span class="badge badge-danger">AGOTADO</span>
                        @else
                            <span class="badge badge-warning">BAJO</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: green;">
                        ✓ Todos los productos tienen stock suficiente
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Del Campo - Sistema E-Commerce | Reporte generado automáticamente | Se recomienda crear solicitudes de reposición
    </div>
</body>
</html>
