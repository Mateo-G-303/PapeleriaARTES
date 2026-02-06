<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use App\Exports\DatosNegocioExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $configuraciones = Configuracion::all();
        return view('admin.configuraciones.index', compact('configuraciones'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'session_lifetime' => 'required|integer|min:1|max:1440',
            'max_login_attempts' => 'required|integer|min:1|max:10',
        ]);

        Configuracion::where('clave', 'session_lifetime')->update(['valor' => $request->session_lifetime]);
        Configuracion::where('clave', 'max_login_attempts')->update(['valor' => $request->max_login_attempts]);

        return redirect()->route('admin.configuraciones.index')->with('success', 'Configuración actualizada correctamente');
    }

    public function backupDatabase()
    {
        $filename = 'backup_artes_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $command = sprintf(
            'PGPASSWORD=%s pg_dump -U %s -h %s -p %s %s > %s',
            env('DB_PASSWORD'),
            env('DB_USERNAME'),
            env('DB_HOST'),
            env('DB_PORT'),
            env('DB_DATABASE'),
            $path
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0 && file_exists($path)) {
            return response()->download($path, $filename)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Error al generar el backup de la base de datos');
    }

    public function exportarDatosNegocio()
    {
        $filename = 'datos_negocio_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new DatosNegocioExport, $filename);
    }
}