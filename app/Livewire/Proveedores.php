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
                'rucprov'       => 'required|digits:13|unique:proveedores,rucprov,' . $this->idprov . ',idprov',
                'nombreprov'    => 'required|string|max:100',
                'correoprov'    => 'required|email|max:100',
                'telefonoprov'  => 'required|digits_between:7,10',
                'direccionprov' => 'required|string|max:200',
            ];
        }

        return [
            'rucprov'       => 'required|digits:13|unique:proveedores,rucprov',
            'nombreprov'    => 'required|string|max:100',
            'correoprov'    => 'required|email|max:100',
            'telefonoprov'  => 'required|digits_between:7,10',
            'direccionprov' => 'required|string|max:200',
        ];
    }
    protected $messages = [
        'rucprov.required' => 'El RUC es obligatorio.',
        'rucprov.digits'   => 'El RUC debe tener exactamente 13 números.',
        'rucprov.unique'   => 'Este RUC ya está registrado.',

        'nombreprov.required' => 'El nombre del proveedor es obligatorio.',

        'correoprov.required' => 'El correo es obligatorio.',
        'correoprov.email'    => 'El correo no es válido.',

        'telefonoprov.required' => 'El teléfono es obligatorio.',
        'telefonoprov.digits_between' =>
            'El teléfono debe tener entre 7 y 10 números.',

        'direccionprov.required' => 'La dirección es obligatoria.',
    ];

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
