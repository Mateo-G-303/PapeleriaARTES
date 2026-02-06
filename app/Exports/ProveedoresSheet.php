<?php

namespace App\Exports;

use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProveedoresSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return Proveedor::all()->map(function ($p) {
            return [
                'ID' => $p->idprov,
                'RUC' => $p->rucprov,
                'Nombre' => $p->nombreprov,
                'Correo' => $p->correoprov,
                'Teléfono' => $p->telefonoprov,
                'Dirección' => $p->direccionprov ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'RUC', 'Nombre', 'Correo', 'Teléfono', 'Dirección'];
    }

    public function title(): string
    {
        return 'Proveedores';
    }
}