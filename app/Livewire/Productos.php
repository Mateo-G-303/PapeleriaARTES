<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;

class Productos extends Component
{
    public $id_producto_editar;
    public $modal = false;

    public $codbarraspro;
    public $nombrepro;
    public $idcat;
    public $precioventapro;
    public $stockpro;

    protected $rules = [
        'codbarraspro' => 'required|max:13',
        'nombrepro' => 'required|min:3',
        'idcat' => 'required',
        'precioventapro' => 'required|numeric',
        'stockpro' => 'required|integer',
    ];

    public function render()
    {
        $productos = Producto::all();
        $categorias = Categoria::all();

        return view('livewire.productos', compact('productos', 'categorias'));
    }

    public function crear()
    {
        if (!auth()->user()->tienePermiso('productos.crear')) {
            abort(403);
        }
        $this->limpiarCampos();
        $this->modal = true;
    }

    public function editar($id)
    {
        if (!auth()->user()->tienePermiso('productos.editar')) {
            abort(403);
        }
        $producto = Producto::find($id);
        
        $this->id_producto_editar = $producto->idpro;
        $this->codbarraspro = $producto->codbarraspro;
        $this->nombrepro = $producto->nombrepro;
        $this->idcat = $producto->idcat;
        $this->precioventapro = $producto->precioventapro;
        $this->stockpro = $producto->stockpro;

        $this->modal = true;
    }

    public function guardar()
    {
        $permiso = $this->id_producto_editar ? 'productos.editar' : 'productos.crear';
        if (!auth()->user()->tienePermiso($permiso)) {
            abort(403);
        }
        
        $this->validate();

        Producto::updateOrCreate(
            ['idpro' => $this->id_producto_editar],
            [
                'codbarraspro' => $this->codbarraspro,
                'nombrepro' => $this->nombrepro,
                'idcat' => $this->idcat,
                'precioventapro' => $this->precioventapro,
                'stockpro' => $this->stockpro,
                'preciominpro' => 0, 
                'preciomaxpro' => 0,
                'estadocatpro' => true,
                'preciocomprapro' => 0,
                'stockminpro' => 5
            ]
        );

        $this->modal = false;
        $this->limpiarCampos();
    }

    public function borrar($id)
    {
        if (!auth()->user()->tienePermiso('productos.eliminar')) {
            abort(403);
        }
        Producto::find($id)->delete();
    }

    public function limpiarCampos()
    {
        $this->id_producto_editar = null;
        $this->codbarraspro = '';
        $this->nombrepro = '';
        $this->idcat = '';
        $this->precioventapro = '';
        $this->stockpro = '';
        $this->modal = false;
    }
}