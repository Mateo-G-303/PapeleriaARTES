<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombrerol' => 'required|max:30',
            'descripcionrol' => 'nullable|max:150',
        ]);

        Rol::create([
            'nombrerol' => $request->nombrerol,
            'descripcionrol' => $request->descripcionrol,
            'estadorol' => true
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Rol creado correctamente');
    }

    public function edit($id)
    {
        $rol = Rol::findOrFail($id);
        return view('admin.roles.edit', compact('rol'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombrerol' => 'required|max:30',
            'descripcionrol' => 'nullable|max:150',
        ]);

        $rol = Rol::findOrFail($id);
        $rol->update([
            'nombrerol' => $request->nombrerol,
            'descripcionrol' => $request->descripcionrol,
            'estadorol' => $request->has('estadorol')
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Rol actualizado correctamente');
    }

    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Rol eliminado correctamente');
    }

    public function toggleStatus($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->update(['estadorol' => !$rol->estadorol]);
        $estado = $rol->estadorol ? 'activado' : 'desactivado';
        return redirect()->route('admin.roles.index')->with('success', "Rol {$estado} correctamente");
    }
}