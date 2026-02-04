<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        
        .info-section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        
        .info-section h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #2c3e50;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .info-row {
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
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
            font-size: 11px;
            text-transform: uppercase;
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
            text-align: right;
        }
        
        .total-row {
            font-size: 14px;
            margin: 5px 0;
        }
        
        .total-final {
            font-size: 20px;
            font-weight: bold;
            color: #27ae60;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
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
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🎨 PAPELERÍA ARTES</div>
            <div class="subtitle">Tu mejor opción en útiles escolares y de oficina</div>
            <div class="subtitle">RUC: 1234567890001</div>
            <div class="subtitle">Dirección: González Suárez #12, Cotacachi - Ecuador - Imbabura</div>
            <div class="subtitle">Teléfono: 0999698582 | Email: papaeleriaArt@gmail.com</div>
        </div>

        <div class="factura-info">
            <table>
                <tr>
                    <td style="width: 60%;">
                        <strong>Fecha:</strong> {{ $venta->fechaven->format('d/m/Y') }}<br>
                        <strong>Hora:</strong> {{ $venta->fechaven->format('H:i:s') }}<br>
                        <strong>Atendido por:</strong> {{ $venta->usuario->name ?? 'N/A' }}
                    </td>
                    <td class="factura-numero" style="width: 40%;">
                        FACTURA<br>
                        #{{ str_pad($venta->idven, 6, '0', STR_PAD_LEFT) }}
                    </td>
                </tr>
            </table>
        </div>

        @if($venta->cliente)
        <div class="info-section">
            <h3>Datos del Cliente</h3>
            <div class="info-row">
                <span class="info-label">Cliente:</span>
                <span>{{ $venta->cliente->nombre }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">RUC/Cédula:</span>
                <span>{{ $venta->cliente->ruc_identificacion }}</span>
            </div>
            @if($venta->cliente->direccion)
            <div class="info-row">
                <span class="info-label">Dirección:</span>
                <span>{{ $venta->cliente->direccion }}</span>
            </div>
            @endif
            @if($venta->cliente->telefono)
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span>{{ $venta->cliente->telefono }}</span>
            </div>
            @endif
        </div>
        @endif

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
                $ivaPorcentaje = $venta->ivaven ?? 12.00;
                $ivaValor = $subtotal * ($ivaPorcentaje / 100);
                $total = $venta->totalven;
            @endphp
            <div class="total-row">
                <strong>Subtotal:</strong> ${{ number_format($subtotal, 2) }}
            </div>
            <div class="total-row">
                <strong>IVA ({{ number_format($ivaPorcentaje, 2) }}%):</strong> ${{ number_format($ivaValor, 2) }}
            </div>
            <div class="total-final">
                TOTAL A PAGAR: ${{ number_format($total, 2) }}
            </div>
        </div>

        <div class="footer">
            <div class="gracias">¡Gracias por su compra!</div>
            <p>Este documento es una representación impresa de una factura electrónica</p>
            <p>Conserve este documento para cualquier reclamo o cambio</p>
            <p>Papelería ARTES - Comprometidos con tu educación y creatividad</p>
            <p>RUC: 1234567890001 | Tel: 0999698582 </p>
        </div>g
    </div>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>