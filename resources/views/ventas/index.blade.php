@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Historial de Ventas</h1>
    @if(Auth::user()->tienePermiso('ventas.crear'))
    <a href="{{ route('ventas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Venta
    </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th># Venta</th>
                    <th>Fecha</th>
                    <th>Vendedor</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $venta)
                <tr>
                    <td>{{ $venta->idven }}</td>
                    <td>{{ $venta->fechaven->format('d/m/Y') }}</td>
                    <td>{{ $venta->usuario->name ?? 'N/A' }}</td>
                    <td>${{ number_format($venta->totalven, 2) }}</td>
                    <td>
                        <a href="{{ route('ventas.show', $venta->idven) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if(Auth::user()->tienePermiso('ventas.facturar'))
                        <a href="{{ route('ventas.factura.ver', $venta->idven) }}" class="btn btn-sm btn-secondary" target="_blank">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                        <a href="{{ route('ventas.factura', $venta->idven) }}" class="btn btn-sm btn-success">
                            <i class="bi bi-download"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No hay ventas registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection