<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Log;
use App\Models\Auditoria; // Asegúrate de tener este modelo
use Illuminate\Support\Facades\DB;

class ReportesGraficos extends Component
{
    public function render()
    {
        // 1. DATOS PARA LOGS (Por Nivel)
        // Agrupamos por idnivel y contamos cuántos hay
        $logsData = Log::select('idnivel', DB::raw('count(*) as total'))
            ->groupBy('idnivel')
            ->get();

        // Preparamos los arrays para el gráfico
        $nivelesLabels = [];
        $nivelesCount = [];

        foreach ($logsData as $log) {
            // Traducimos el ID al nombre (puedes usar relación si prefieres)
            $nombre = match ($log->idnivel) {
                1 => 'Crítico',
                2 => 'Advertencia',
                default => 'Informativo',
            };
            $nivelesLabels[] = $nombre;
            $nivelesCount[] = $log->total;
        }

        // 2. DATOS PARA AUDITORIA (Por Usuario - Top 5)
        // Asumiendo que tu tabla auditoria tiene 'user_id' o un campo de usuario
        // Si tu auditoria guarda el nombre directo, usa ese campo.
        // Aquí cuento cuántas acciones ha hecho cada usuario
        $auditoriaData = DB::table('auditoria') // O usa el modelo Auditoria::...
            ->join('users', 'auditoria.user_id', '=', 'users.id') // Ajusta las claves foráneas
            ->select('users.name', DB::raw('count(*) as total'))
            ->groupBy('users.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $usuariosLabels = $auditoriaData->pluck('name');
        $accionesCount = $auditoriaData->pluck('total');

        return view('livewire.reportes-graficos', [
            'nivelesLabels' => $nivelesLabels,
            'nivelesCount' => $nivelesCount,
            'usuariosLabels' => $usuariosLabels,
            'accionesCount' => $accionesCount,
        ]);
    }
}
