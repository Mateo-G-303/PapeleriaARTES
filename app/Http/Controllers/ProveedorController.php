<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        if (!auth()->user()->tienePermiso('proveedores.ver')) {
            abort(403);
        }
        $proveedores = Proveedor::all();
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        if (!auth()->user()->tienePermiso('proveedores.crear')) {
            abort(403);
        }
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->tienePermiso('proveedores.crear')) {
            abort(403);
        }
        
        $request->validate([
            'rucprov' => 'required|unique:proveedores',
            'nombreprov' => 'required',
            'correoprov' => 'required|email',
            'telefonoprov' => 'required'
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor registrado correctamente');
    }

    public function edit($id)
    {
        if (!auth()->user()->tienePermiso('proveedores.editar')) {
            abort(403);
        }
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->tienePermiso('proveedores.editar')) {
            abort(403);
        }
        
        $proveedor = Proveedor::findOrFail($id);

        $request->validate([
            'rucprov' => 'required|unique:proveedores,rucprov,' . $proveedor->idprov . ',idprov',
            'nombreprov' => 'required',
            'correoprov' => 'required|email',
            'telefonoprov' => 'required'
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado');
    }

    public function destroy($id)
    {
        if (!auth()->user()->tienePermiso('proveedores.eliminar')) {
            abort(403);
        }
        
        Proveedor::findOrFail($id)->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado');
    }
}