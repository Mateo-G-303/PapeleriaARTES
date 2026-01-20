<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura #{{ $venta->idven }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
        }
        .subtitle {
            color: #7f8c8d;
            margin-top: 5px;
        }
        .factura-info {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .factura-info table {
            width: 100%;
        }
        .factura-info td {
            padding: 5px 0;
        }
        .factura-numero {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
            text-align: right;
        }
        .productos-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .productos-table th {
            background-color: #2c3e50;
            color: white;
            padding: 12px 8px;
            text-align: left;
        }
        .productos-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
        }
        .productos-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .totales {
            margin-top: 20px;
            width: 300px;
            margin-left: auto;
        }
        .totales table {
            width: 100%;
        }
        .totales td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .totales .total-final td {
            font-size: 16px;
            font-weight: bold;
            color: #27ae60;
            border-top: 2px solid #333;
            border-bottom: none;
            padding-top: 12px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #7f8c8d;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .gracias {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🎨 PAPELERÍA ARTES</div>
            <div class="subtitle">Tu mejor opción en útiles escolares y de oficina</div>
            <div class="subtitle">Dirección: Av. Principal #123 | Tel: (02) 123-4567</div>
        </div>

        <div class="factura-info">
            <table>
                <tr>
                    <td>
                        <strong>Fecha:</strong> {{ $venta->fechaven->format('d/m/Y') }}<br>
                        <strong>Hora:</strong> {{ now()->format('H:i:s') }}<br>
                        <strong>Atendido por:</strong> {{ $venta->usuario->name ?? 'N/A' }}
                    </td>
                    <td class="factura-numero">
                        FACTURA<br>
                        #{{ str_pad($venta->idven, 6, '0', STR_PAD_LEFT) }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="productos-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th class="text-right">P. Unit.</th>
                    <th class="text-right">Cant.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->codbarraspro ?? 'N/A' }}</td>
                    <td>{{ $detalle->producto->nombrepro ?? 'N/A' }}</td>
                    <td class="text-right">${{ number_format($detalle->preciounitariodven, 2) }}</td>
                    <td class="text-right">{{ $detalle->cantidaddven }}</td>
                    <td class="text-right">${{ number_format($detalle->subtotaldven, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totales">
            @php
                $subtotal = $venta->subtotalven ?? $venta->detalles->sum('subtotaldven');
                $ivaPorcentaje = $venta->ivaven ?? 12;
                $ivaValor = $subtotal * ($ivaPorcentaje / 100);
                $total = $venta->totalven;
            @endphp
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>IVA ({{ number_format($ivaPorcentaje, 2) }}%):</td>
                    <td class="text-right">${{ number_format($ivaValor, 2) }}</td>
                </tr>
                <tr class="total-final">
                    <td>TOTAL A PAGAR:</td>
                    <td class="text-right">${{ number_format($total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <div class="gracias">¡Gracias por su compra!</div>
            <p>Este documento es una representación impresa de una factura electrónica</p>
            <p>Conserve este documento para cualquier reclamo o cambio</p>
            <p>Papelería ARTES - RUC: 1234567890001</p>
        </div>
    </div>
</body>
</html>