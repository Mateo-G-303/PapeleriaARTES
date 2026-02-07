<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Log;
use App\Models\Auditoria; // Asegúrate de que el modelo exista
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesGraficos extends Component
{

    public function render()
    {
        // 1. LOGS (Sin cambios)
        $logsData = Log::select('idnivel', DB::raw('count(*) as total'))
            ->groupBy('idnivel')
            ->get();

        $nivelesLabels = [];
        $nivelesCount = [];

        foreach ($logsData as $log) {
            $nombre = match ($log->idnivel) {
                1 => 'Crítico',
                2 => 'Advertencia',
                default => 'Informativo',
            };
            $nivelesLabels[] = $nombre;
            $nivelesCount[] = $log->total;
        }

        // 2. USUARIOS (Sin cambios)
        $auditoriaData = DB::table('auditoria')
            ->join('users', 'auditoria.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('count(*) as total'))
            ->groupBy('users.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $usuariosLabels = $auditoriaData->pluck('name');
        $usuariosCount = $auditoriaData->pluck('total');

        // 3. TENDENCIA SEMANAL (Sin cambios)
        $logsPorDia = Log::select(
            DB::raw('DATE(fechalogs) as fecha'),
            DB::raw('count(*) as total')
        )
            ->where('fechalogs', '>=', \Carbon\Carbon::now()->subDays(7))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $trendLabels = $logsPorDia->pluck('fecha');
        $trendCount = $logsPorDia->pluck('total');

        // --- 4. TOP ACCIONES (CORREGIDO: Usando 'accionaud') ---
        $topAcciones = DB::table('auditoria')
            ->select('accionaud', DB::raw('count(*) as total')) // <--- Aquí usamos tu columna real
            ->groupBy('accionaud')                              // <--- Agrupamos por esa columna
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Extraemos los nombres de las acciones desde la columna correcta
        $accionesLabels = $topAcciones->pluck('accionaud');
        $accionesCount = $topAcciones->pluck('total');

        // 5. WIDGETS
        $totalHoy = Log::whereDate('fechalogs', \Carbon\Carbon::today())->count();
        $totalCriticos = Log::where('idnivel', 1)->count();
        $totalUsuarios = \App\Models\User::count();

        return view('livewire.reportes-graficos', [
            'nivelesLabels' => $nivelesLabels,
            'nivelesCount' => $nivelesCount,
            'usuariosLabels' => $usuariosLabels,
            'usuariosCount' => $usuariosCount,
            'trendLabels' => $trendLabels,
            'trendCount' => $trendCount,
            'accionesLabels' => $accionesLabels,
            'accionesCount' => $accionesCount,
            'totalHoy' => $totalHoy,
            'totalCriticos' => $totalCriticos,
            'totalUsuarios' => $totalUsuarios,
        ]);
    }
}
