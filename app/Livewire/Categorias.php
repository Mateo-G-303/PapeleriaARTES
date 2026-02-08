<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithPagination;

class Categorias extends Component
{
    public $idcat_editar, $nombrecat, $descripcioncat;
    public $modal = false;

    //Paginacion y buscador
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'nombrecat' => 'required|min:3|max:50',
        'descripcioncat' => 'nullable|max:100',
    ];

    

    public function render()
    {
        $categorias = Categoria::where('activocat', true)
        ->when($this->search, function ($query){
                $query->where('nombrecat', 'ILIKE', '%' . $this->search . '%');
            })
            ->orderBy('nombrecat')
            ->paginate(7);

        return view('livewire.categorias', compact('categorias'));
    }

    public function crear()
    {
        if (!auth()->user()->tienePermiso('categorias.crear')) {
            abort(403);
        }
        $this->limpiarCampos();
        $this->search ='';
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
                'activocat' => true
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
        $categoria = Categoria::findOrFail($id);

        $categoria->activocat = false;
        $categoria->save();
    }

    public function limpiarCampos()
    {
        $this->idcat_editar = null;
        $this->nombrecat = '';
        $this->descripcioncat = '';
        $this->modal = false;
    }
}