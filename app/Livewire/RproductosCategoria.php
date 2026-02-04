<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RproductosCategoria extends Component
{
    public $categorias = [];
    public $totales = [];

    public function mount()
    {
        $data = DB::select(
            "SELECT * FROM dashboard.fn_productos_por_categoria()"
        );

        $this->categorias = array_column($data, 'categoria');
        $this->totales    = array_column($data, 'total_productos');
    }

    public function render()
    {
        return view('livewire.rproductos-categoria');
    }
}