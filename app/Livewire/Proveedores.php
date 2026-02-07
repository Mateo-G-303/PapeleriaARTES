<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Proveedor;
use Livewire\WithPagination;

class Proveedores extends Component
{
    public $modal = false;

    public $idprov;
    public $rucprov;
    public $nombreprov;
    public $correoprov;
    public $telefonoprov;
    public $direccionprov;
    //Para buscar
    public $search = '';
    //Paginacion
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    public function updatedSearch()
    {
        $this->resetPage();
    }

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
         $proveedores = Proveedor::where('activoprov', true)
         ->when($this->search, function ($query){
                $query->where(function ($q) {
                    $q->where('rucprov', 'ILIKE', '%' . $this->search . '%')
                      ->orWhere('nombreprov', 'ILIKE', '%' . $this->search . '%')
                      ->orWhere('telefonoprov', 'ILIKE', '%' . $this->search . '%')
                      ->orWhere('direccionprov', 'ILIKE', '%' . $this->search . '%');
                });
            })
            ->orderBy('nombreprov')
            ->paginate(2);

        return view('livewire.proveedores', compact('proveedores'));
    }

    public function abrirModal()
    {
        $this->search ='';
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
                'activoprov'    => true
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
         $proveedor = Proveedor::findOrFail($id);

        if (!$proveedor->activoprov) {
            return;
        }

        $proveedor->activoprov = false;
        $proveedor->save();
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
