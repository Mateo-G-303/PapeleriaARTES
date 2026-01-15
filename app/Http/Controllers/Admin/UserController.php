<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('rol')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Rol::where('estadorol', true)->get();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|max:10|unique:users',
            'name' => 'required|max:100',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:8',
            'idrol' => 'required|exists:roles,idrol',
        ]);

        User::create([
            'cedula' => $request->cedula,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'idrol' => $request->idrol,
            'intentos_fallidos' => 0,
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Rol::where('estadorol', true)->get();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $id,
            'idrol' => 'required|exists:roles,idrol',
        ]);

        $datos = [
            'name' => $request->name,
            'email' => $request->email,
            'idrol' => $request->idrol,
        ];

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $usuario->update($datos);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }

    public function desbloquear($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->update([
            'intentos_fallidos' => 0,
            'bloqueado_hasta' => null
        ]);
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario desbloqueado correctamente');
    }
}