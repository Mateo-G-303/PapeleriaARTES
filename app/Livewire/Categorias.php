<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Categoria;

class Categorias extends Component
{
    public $idcat_editar, $nombrecat, $descripcioncat;
    public $modal = false;

    protected $rules = [
        'nombrecat' => 'required|min:3|max:50',
        'descripcioncat' => 'nullable|max:100',
    ];

    public function render()
    {
        return view('livewire.categorias', [
            'categorias' => Categoria::all()
        ]);
    }

    public function crear()
    {
        if (!auth()->user()->tienePermiso('categorias.crear')) {
            abort(403);
        }
        $this->limpiarCampos();
        $this->modal = true;
    }

    public function editar($id)
    {
        if (!auth()->user()->tienePermiso('categorias.editar')) {
            abort(403);
        }
        $cat = Categoria::find($id);
        $this->idcat_editar = $cat->idcat;
        $this->nombrecat = $cat->nombrecat;
        $this->descripcioncat = $cat->descripcioncat;
        $this->modal = true;
    }

    public function guardar()
    {
        $permiso = $this->idcat_editar ? 'categorias.editar' : 'categorias.crear';
        if (!auth()->user()->tienePermiso($permiso)) {
            abort(403);
        }
        
        $this->validate();

        Categoria::updateOrCreate(
            ['idcat' => $this->idcat_editar],
            [
                'nombrecat' => $this->nombrecat,
                'descripcioncat' => $this->descripcioncat,
            ]
        );

        $this->modal = false;
        $this->limpiarCampos();
    }

    public function borrar($id)
    {
        if (!auth()->user()->tienePermiso('categorias.eliminar')) {
            abort(403);
        }
        Categoria::find($id)->delete();
    }

    public function limpiarCampos()
    {
        $this->idcat_editar = null;
        $this->nombrecat = '';
        $this->descripcioncat = '';
        $this->modal = false;
    }
}