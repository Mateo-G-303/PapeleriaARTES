<?php

namespace App\Exports;

use App\Models\Compra;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ComprasSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return Compra::with(['proveedor', 'detalles.producto'])->get()->map(function ($c) {
            return [
                'ID' => $c->idcompra,
                'Fecha' => $c->fechacompra,
                'Proveedor' => $c->proveedor->nombreprov ?? 'N/A',
                'Total' => $c->totalcompra,
                'Productos' => $c->detalles->map(fn($d) => $d->producto->nombrepro . ' x' . $d->cantidad)->implode(', '),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Fecha', 'Proveedor', 'Total', 'Productos'];
    }

    public function title(): string
    {
        return 'Compras';
    }
}