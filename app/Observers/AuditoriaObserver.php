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
        if ($model->getTable() === 'auditoria') return;

        $usuarioId = Auth::id();

        // Intentamos el registro normal
        try {
            DB::table('auditoria')->insert([
                'user_id'      => $usuarioId,
                'accionaud'    => $accion,
                'tablaaud'     => $model->getTable(),
                'fechahoraaud' => now(),
            ]);
        } catch (\Exception $e) {
            // Si falla (por ejemplo, por la paradoja de borrar tu propia cuenta)
            // registramos el evento sin el ID para no romper la transacción
            DB::table('auditoria')->insert([
                'user_id'      => null,
                'accionaud'    => $accion . " (Autogestionado)",
                'tablaaud'     => $model->getTable(),
                'fechahoraaud' => now(),
            ]);
        }
    }
}
