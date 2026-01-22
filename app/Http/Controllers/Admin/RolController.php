<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\Permiso;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::with(['permisos', 'users'])->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
{
    $permisosAgrupados = Permiso::getAgrupados();
    
    // Excluir módulos reservados para administrador
    $modulosRestringidos = ['Usuarios', 'Roles', 'Configuraciones'];
    $permisosAgrupados = collect($permisosAgrupados)->except($modulosRestringidos);
    
    return view('admin.roles.create', compact('permisosAgrupados'));
}

    public function store(Request $request)
    {
        $request->validate([
            'nombrerol' => 'required|max:30|unique:roles,nombrerol',
            'descripcionrol' => 'nullable|max:150',
            'permisos' => 'array',
        ]);

        $rol = Rol::create([
            'nombrerol' => $request->nombrerol,
            'descripcionrol' => $request->descripcionrol,
            'estadorol' => true
        ]);

        // Sincronizar permisos
        if ($request->has('permisos')) {
            $rol->sincronizarPermisos($request->permisos);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol creado correctamente');
    }

    public function edit($id)
{
    $rol = Rol::with('permisos')->findOrFail($id);
    $permisosAgrupados = Permiso::getAgrupados();
    
    // Excluir módulos reservados para administrador
    $modulosRestringidos = ['Usuarios', 'Roles', 'Configuraciones'];
    $permisosAgrupados = collect($permisosAgrupados)->except($modulosRestringidos);
    
    $permisosRol = $rol->getPermisosIds();
    
    return view('admin.roles.edit', compact('rol', 'permisosAgrupados', 'permisosRol'));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombrerol' => 'required|max:30|unique:roles,nombrerol,' . $id . ',idrol',
            'descripcionrol' => 'nullable|max:150',
            'permisos' => 'array',
        ]);

        $rol = Rol::findOrFail($id);
        
        // No permitir editar el rol Administrador principal
        if ($rol->idrol === 1 && $request->nombrerol !== 'Administrador') {
            return redirect()->back()->with('error', 'No se puede cambiar el nombre del rol Administrador principal');
        }

        $rol->update([
            'nombrerol' => $request->nombrerol,
            'descripcionrol' => $request->descripcionrol,
            'estadorol' => $request->has('estadorol')
        ]);

        // Sincronizar permisos (excepto para Administrador que siempre tiene todos)
        if ($rol->idrol !== 1) {
            $rol->sincronizarPermisos($request->permisos ?? []);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol actualizado correctamente');
    }

    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        
        // No permitir eliminar roles del sistema
        if (in_array($rol->idrol, [1, 2, 3])) {
            return redirect()->route('admin.roles.index')->with('error', 'No se pueden eliminar los roles del sistema');
        }

        // Verificar si hay usuarios con este rol
        if ($rol->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', 'No se puede eliminar un rol que tiene usuarios asignados');
        }

        $rol->permisos()->detach(); // Eliminar relaciones en tabla pivote
        $rol->delete();
        
        return redirect()->route('admin.roles.index')->with('success', 'Rol eliminado correctamente');
    }

    public function toggleStatus($id)
    {
        $rol = Rol::findOrFail($id);
        
        // No permitir desactivar el rol Administrador
        if ($rol->idrol === 1) {
            return redirect()->route('admin.roles.index')->with('error', 'No se puede desactivar el rol Administrador');
        }

        $rol->update(['estadorol' => !$rol->estadorol]);
        $estado = $rol->estadorol ? 'activado' : 'desactivado';
        return redirect()->route('admin.roles.index')->with('success', "Rol {$estado} correctamente");
    }
}