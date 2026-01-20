@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Detalle de Venta N# - {{ $venta->idven }}</h1>
    <div>
        <a href="{{ route('ventas.factura.ver', $venta->idven) }}" class="btn btn-warning" target="_blank">
            <i class="bi bi-file-pdf"></i> Ver Factura
        </a>
        <a href="{{ route('ventas.factura', $venta->idven) }}" class="btn btn-danger">
            <i class="bi bi-download"></i> Descargar PDF
        </a>
        <a href="{{ route('ventas.index') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-dark">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Información de la Venta</h5>
            </div>
            <div class="card-body border border-dark">
                <p><strong>Fecha:</strong> {{ $venta->fechaven->format('d/m/Y') }}</p>
                <p><strong>Vendedor:</strong> {{ $venta->usuario->name ?? 'N/A' }}</p>
                <p><strong>Total:</strong> <span class="fs-4 text-success">${{ number_format($venta->totalven, 2) }}</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-dark">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Productos Vendidos</h5>
            </div>
            <div class="card-body p-0 border border-dark">
                <table class="table mb-0 table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th class="border-dark">Código</th>
                            <th class="border-dark">Producto</th>
                            <th class="border-dark">Precio Unit.</th>
                            <th class="border-dark">Cantidad</th>
                            <th class="border-dark">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $detalle)
                        <tr>
                            <td class="border-dark">{{ $detalle->producto->codbarraspro ?? 'N/A' }}</td>
                            <td class="border-dark">{{ $detalle->producto->nombrepro ?? 'N/A' }}</td>
                            <td class="border-dark">${{ number_format($detalle->preciounitariodven, 2) }}</td>
                            <td class="border-dark">{{ $detalle->cantidaddven }}</td>
                            <td class="border-dark">${{ number_format($detalle->subtotaldven, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end border-dark">TOTAL:</th>
                            <th class="border-dark">${{ number_format($venta->totalven, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection