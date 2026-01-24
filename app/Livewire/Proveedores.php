<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Proveedor;

class Proveedores extends Component
{
    public $proveedores;
    public $modal = false;

    public $idprov;
    public $rucprov;
    public $nombreprov;
    public $correoprov;
    public $telefonoprov;
    public $direccionprov;

    protected function rules()
    {
        if ($this->idprov) {
            return [
                'rucprov'       => 'required|unique:proveedores,rucprov,' . $this->idprov . ',idprov',
                'nombreprov'    => 'required',
                'correoprov'    => 'required|email',
                'telefonoprov'  => 'required',
                'direccionprov' => 'required',
            ];
        }

        return [
            'rucprov'       => 'required|unique:proveedores,rucprov',
            'nombreprov'    => 'required',
            'correoprov'    => 'required|email',
            'telefonoprov'  => 'required',
            'direccionprov' => 'required',
        ];
    }

    public function render()
    {
        $this->proveedores = Proveedor::all();
        return view('livewire.proveedores');
    }

    public function abrirModal()
    {
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->limpiarCampos();
    }

    public function guardar()
    {
        $this->validate();

        Proveedor::updateOrCreate(
            ['idprov' => $this->idprov],
            [
                'rucprov'       => $this->rucprov,
                'nombreprov'    => $this->nombreprov,
                'correoprov'    => $this->correoprov,
                'telefonoprov'  => $this->telefonoprov,
                'direccionprov' => $this->direccionprov,
            ]
        );

        $this->cerrarModal();
    }

    public function editar($id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $this->idprov        = $proveedor->idprov;
        $this->rucprov       = $proveedor->rucprov;
        $this->nombreprov    = $proveedor->nombreprov;
        $this->correoprov    = $proveedor->correoprov;
        $this->telefonoprov  = $proveedor->telefonoprov;
        $this->direccionprov = $proveedor->direccionprov;

        $this->modal = true;
    }

    public function borrar($id)
    {
        Proveedor::findOrFail($id)->delete();
    }

    public function limpiarCampos()
    {
        $this->idprov        = null;
        $this->rucprov       = '';
        $this->nombreprov    = '';
        $this->correoprov    = '';
        $this->telefonoprov  = '';
        $this->direccionprov = '';
    }
}
