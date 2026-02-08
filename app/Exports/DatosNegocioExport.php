<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DatosNegocioExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Productos' => new ProductosSheet(),
            'Categorias' => new CategoriasSheet(),
            'Ventas' => new VentasSheet(),
            'Compras' => new ComprasSheet(),
            'Proveedores' => new ProveedoresSheet(),
        ];
    }
}