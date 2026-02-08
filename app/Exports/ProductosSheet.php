<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductosSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return Producto::with('categoria')->get()->map(function ($p) {
            return [
                'ID' => $p->idpro,
                'Código' => $p->codbarraspro,
                'Nombre' => $p->nombrepro,
                'Categoría' => $p->categoria->nombrecat ?? 'Sin categoría',
                'Precio Compra' => $p->preciocomprapro,
                'Precio Venta' => $p->precioventapro,
                'Stock' => $p->stockpro,
                'Stock Mínimo' => $p->stockminpro,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Código', 'Nombre', 'Categoría', 'Precio Compra', 'Precio Venta', 'Stock', 'Stock Mínimo'];
    }

    public function title(): string
    {
        return 'Productos';
    }
}