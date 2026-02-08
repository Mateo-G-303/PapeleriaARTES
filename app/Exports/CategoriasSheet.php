<?php

namespace App\Exports;

use App\Models\Categoria;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class CategoriasSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return Categoria::all()->map(function ($c) {
            return [
                'ID' => $c->idcat,
                'Nombre' => $c->nombrecat,
                'Descripción' => $c->descripcioncat,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Descripción'];
    }

    public function title(): string
    {
        return 'Categorías';
    }
}