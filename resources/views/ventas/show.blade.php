@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Detalle de Venta #{{ $venta->idven }}</h1>
    <div>
        <a href="{{ route('ventas.factura.ver', $venta->idven) }}" class="btn btn-secondary" target="_blank">
            <i class="bi bi-file-pdf"></i> Ver Factura
        </a>
        <a href="{{ route('ventas.factura', $venta->idven) }}" class="btn btn-success">
            <i class="bi bi-download"></i> Descargar PDF
        </a>
        <a href="{{ route('ventas.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Información de la Venta</h5>
            </div>
            <div class="card-body">
                <p><strong>Fecha:</strong> {{ $venta->fechaven->format('d/m/Y') }}</p>
                <p><strong>Vendedor:</strong> {{ $venta->usuario->name ?? 'N/A' }}</p>
                <p><strong>Total:</strong> <span class="fs-4 text-success">${{ number_format($venta->totalven, 2) }}</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Productos Vendidos</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Precio Unit.</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->codbarraspro ?? 'N/A' }}</td>
                            <td>{{ $detalle->producto->nombrepro ?? 'N/A' }}</td>
                            <td>${{ number_format($detalle->preciounitariodven, 2) }}</td>
                            <td>{{ $detalle->cantidaddven }}</td>
                            <td>${{ number_format($detalle->subtotaldven, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">TOTAL:</th>
                            <th>${{ number_format($venta->totalven, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection