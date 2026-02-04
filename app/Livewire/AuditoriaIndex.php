<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Auditoria;

class AuditoriaIndex extends Component
{
    use WithPagination;

    public function render()
    {
        // Traemos los registros ordenados del más reciente al más antiguo
        $registros = Auditoria::with('user') // Cargamos el usuario para evitar consultas lentas
            ->orderBy('fechahoraaud', 'desc')
            ->paginate(10);

        return view('livewire.auditoria-index', [
            'auditorias' => $registros
        ]);
    }
}
