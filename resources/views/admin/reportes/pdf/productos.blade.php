<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos Más Vendidos</title>
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
        .text-center {
            text-align: center;
        }
        .ranking {
            background: #4A7C23;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: inline-block;
            text-align: center;
            line-height: 25px;
            font-weight: bold;
        }
        .categoria-section {
            margin-top: 30px;
        }
        .categoria-section h3 {
            color: #4A7C23;
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
        <h1>🌽 Del Campo - Productos Más Vendidos</h1>
        <p>Período: {{ $fechaDesde }} al {{ $fechaHasta }}</p>
        <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <h3>Top 20 Productos Más Vendidos</h3>
    <table>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th class="text-right">Precio Unit.</th>
                <th class="text-right">Cant. Vendida</th>
                <th class="text-right">Total (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $index => $producto)
                <tr>
                    <td class="text-center">
                        <span class="ranking">{{ $index + 1 }}</span>
                    </td>
                    <td><strong>{{ $producto->nombre }}</strong></td>
                    <td>{{ App\Models\Producto::CATEGORIAS[$producto->categoria] ?? $producto->categoria }}</td>
                    <td class="text-right">{{ number_format($producto->precio, 2) }}</td>
                    <td class="text-right">{{ $producto->cantidad_vendida }}</td>
                    <td class="text-right"><strong>{{ number_format($producto->total_vendido, 2) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay datos en este período</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(isset($porCategoria) && count($porCategoria) > 0)
    <div class="categoria-section">
        <h3>Resumen por Categoría</h3>
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th class="text-right">Cantidad Vendida</th>
                    <th class="text-right">Total (Bs)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($porCategoria as $cat)
                    <tr>
                        <td>{{ App\Models\Producto::CATEGORIAS[$cat->categoria] ?? $cat->categoria }}</td>
                        <td class="text-right">{{ $cat->cantidad }}</td>
                        <td class="text-right">{{ number_format($cat->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        Del Campo - Sistema E-Commerce | Reporte generado automáticamente
    </div>
</body>
</html>
