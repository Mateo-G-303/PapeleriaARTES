<?php

// app/Http/Controllers/ProveedorController.php
namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
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
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
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
        Proveedor::findOrFail($id)->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado');
    }
}
