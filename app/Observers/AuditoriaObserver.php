<?php

namespace App\Observers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // <--- AGREGA ESTO

class AuditoriaObserver
{
    public function created(Model $model)
    {
        $this->registrarAuditoria('CREAR', $model);
    }

    public function updated(Model $model)
    {
        $this->registrarAuditoria('ACTUALIZAR', $model);
    }

    public function deleted(Model $model)
    {
        $this->registrarAuditoria('ELIMINAR', $model);
    }

    protected function registrarAuditoria($accion, $model)
    {
        // Evitamos auditar la propia tabla de auditoría para no crear un bucle infinito
        if ($model->getTable() === 'auditoria') return;

        DB::table('auditoria')->insert([
            'user_id'      => Auth::id(),              // <--- CAMBIO AQUÍ (Usando la Facade)      
            'accionaud'    => $accion,
            'tablaaud'     => $model->getTable(),      // Tu columna tablaaud (detecta nombre automático)
            'fechahoraaud' => now(),                   // Tu columna fechahoraaud
            // Si quieres guardar el dato viejo/nuevo en un futuro, podrías agregarlo aquí
        ]);
    }
}
