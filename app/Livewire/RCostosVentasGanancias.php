<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RCostosVentasGanancias extends Component
{
    public $anio = 2026;

    public $meses = [];
    public $costos = [];
    public $ventas = [];
    public $ganancias = [];

    public function mount()
    {
        $data = DB::select(
            "SELECT * FROM reporte_financiero_mensual(?) ORDER BY mes",
            [$this->anio]
        );

        $this->meses     = array_column($data, 'mes');
        $this->costos    = array_column($data, 'total_costos');
        $this->ventas    = array_column($data, 'total_ventas');
        $this->ganancias = array_column($data, 'ganancias');
    }

    public function render()
    {
        return view('livewire.r-costos-ventas-ganancias');
    }
}
