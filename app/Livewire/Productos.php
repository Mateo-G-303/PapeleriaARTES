<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;

class Productos extends Component
{
    // Variables para el Formulario
    public $id_producto_editar; // Para saber si estamos editando
    public $modal = false;      // Controla si la ventana está abierta

    // Campos de la base de datos
    public $codbarraspro;
    public $nombrepro;
    public $idcat;
    public $precioventapro;
    public $stockpro;
    // (Puedes añadir más campos como preciocompra, stockmin, etc. aquí)

    // Reglas de validación
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
        $categorias = Categoria::all(); // Necesario para el <select> del formulario

        return view('livewire.productos', compact('productos', 'categorias'));
    }

    // Abrir Modal para CREAR
    public function crear()
    {
        $this->limpiarCampos();
        $this->modal = true;
    }

    // Abrir Modal para EDITAR
    public function editar($id)
    {
        $producto = Producto::find($id);
        
        $this->id_producto_editar = $producto->idpro;
        $this->codbarraspro = $producto->codbarraspro;
        $this->nombrepro = $producto->nombrepro;
        $this->idcat = $producto->idcat;
        $this->precioventapro = $producto->precioventapro;
        $this->stockpro = $producto->stockpro;

        $this->modal = true;
    }

    // Guardar o Actualizar
    public function guardar()
    {
        $this->validate();

        Producto::updateOrCreate(
            ['idpro' => $this->id_producto_editar], // Si existe este ID, actualiza
            [
                'codbarraspro' => $this->codbarraspro,
                'nombrepro' => $this->nombrepro,
                'idcat' => $this->idcat,
                'precioventapro' => $this->precioventapro,
                'stockpro' => $this->stockpro,
                // Valores por defecto para lo que no pedimos en el form:
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

    // Borrar Producto
    public function borrar($id)
    {
        Producto::find($id)->delete();
    }

    // Cerrar Modal y limpiar
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