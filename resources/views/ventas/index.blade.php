@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Historial de Ventas</h1>
    <a href="{{ route('ventas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Venta
    </a>
</div>

<div class="card border-dark shadow-sm">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">
            <i class="bi bi-table me-2"></i>Listado de Ventas
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="border-dark text-center">Venta</th>
                        <th class="border-dark">Fecha</th>
                        <th class="border-dark">Vendedor</th>
                        <th class="border-dark">Total</th>
                        <th class="border-dark text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    <tr>
                        <td class="border-dark text-center fw-bold">N# - {{ $venta->idven }}</td>
                        <td class="border-dark">{{ $venta->fechaven->format('d/m/Y') }}</td>
                        <td class="border-dark">{{ $venta->usuario->name ?? 'N/A' }}</td>
                        <td class="border-dark fw-bold text-purple-dark">${{ number_format($venta->totalven, 2) }}</td>
                        <td class="border-dark text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('ventas.show', $venta->idven) }}" 
                                   class="btn btn-warning" 
                                   data-bs-toggle="tooltip" 
                                   title="Ver Detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('ventas.factura.ver', $venta->idven) }}" 
                                   class="btn btn-success" 
                                   target="_blank"
                                   data-bs-toggle="tooltip" 
                                   title="Ver Factura">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <a href="{{ route('ventas.factura', $venta->idven) }}" 
                                   class="btn btn-danger"
                                   data-bs-toggle="tooltip" 
                                   title="Descargar PDF">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 border-dark">
                            <i class="bi bi-receipt display-5 text-muted d-block mb-2"></i>
                            <span class="text-muted">No hay ventas registradas</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table th, .table td {
        padding: 12px 16px;
        vertical-align: middle;
    }
    
    .btn-group .btn {
        border-radius: 4px;
        margin: 0 2px;
    }
    
    .card {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .text-purple-dark {
        color: #4b0082 !important; /* Color morado oscuro */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection