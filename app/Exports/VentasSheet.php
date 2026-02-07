<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class VentasSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return Venta::with(['usuario', 'detalles.producto'])->get()->map(function ($v) {
            return [
                'ID' => $v->idven,
                'Fecha' => $v->fechaven->format('d/m/Y H:i'),
                'Vendedor' => $v->usuario->name ?? 'N/A',
                'Total' => $v->totalven,
                'Productos' => $v->detalles->map(fn($d) => $d->producto->nombrepro . ' x' . $d->cantidaddven)->implode(', '),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Fecha', 'Vendedor', 'Total', 'Productos'];
    }

    public function title(): string
    {
        return 'Ventas';
    }
}