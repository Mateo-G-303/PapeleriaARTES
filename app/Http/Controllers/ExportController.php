<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
// Si no tienes modelo Auditoria, usaremos DB::table, que es muy eficiente
use Illuminate\Support\Facades\DB; 
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    // --- 1. EXPORTAR LOGS (Tabla: logs + nivellogs) ---
    public function exportarLogs()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            
            // Encabezados para Excel (UTF-8 BOM)
            fputs($handle, "\xEF\xBB\xBF"); 
            fputcsv($handle, ['ID Log', 'Usuario', 'Nivel', 'Mensaje', 'Fecha y Hora']);

            // Consulta optimizada
            $query = Log::join('users', 'logs.user_id', '=', 'users.id')
                ->join('nivellogs', 'logs.idnivel', '=', 'nivellogs.idnivel') // Relación con tu tabla 'nivellogs'
                ->select(
                    'logs.idlogs',                 // Tu PK
                    'users.name as nombre_usuario',
                    'nivellogs.nombrenivel',       // Tu columna de nivel
                    'logs.mensajelogs',            // Tu columna de mensaje
                    'logs.fechalogs'               // Tu fecha
                )
                ->orderBy('logs.fechalogs', 'desc');

            // Usamos cursor() para no saturar la memoria
            foreach ($query->cursor() as $log) {
                fputcsv($handle, [
                    $log->idlogs,
                    $log->nombre_usuario,
                    $log->nombrenivel,
                    $log->mensajelogs,
                    $log->fechalogs,
                ]);
            }

            fclose($handle);
        }, 'logs_seguridad_' . date('Y-m-d_H-i') . '.csv');
    }

    // --- 2. EXPORTAR AUDITORÍA (Tabla: auditoria) ---
    public function exportarAuditoria()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            
            // Encabezados
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['ID', 'Usuario', 'Acción', 'Tabla Afectada', 'Fecha y Hora']);

            // Consulta directa a la tabla 'auditoria'
            $query = DB::table('auditoria')
                ->join('users', 'auditoria.user_id', '=', 'users.id')
                ->select(
                    'auditoria.idaud',             // Tu PK
                    'users.name as nombre_usuario',
                    'auditoria.accionaud',         // Tu columna de acción
                    'auditoria.tablaaud',          // Tu columna de tabla
                    'auditoria.fechahoraaud'       // Tu fecha
                )
                ->orderBy('auditoria.fechahoraaud', 'desc');

            foreach ($query->cursor() as $aud) {
                fputcsv($handle, [
                    $aud->idaud,
                    $aud->nombre_usuario,
                    $aud->accionaud,
                    $aud->tablaaud,
                    $aud->fechahoraaud,
                ]);
            }

            fclose($handle);
        }, 'auditoria_papeleria_' . date('Y-m-d_H-i') . '.csv');
    }
}