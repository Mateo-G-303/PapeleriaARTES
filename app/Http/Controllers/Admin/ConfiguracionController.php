<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;

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
}