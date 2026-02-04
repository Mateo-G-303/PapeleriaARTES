<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
class ReporteCompras extends Component
{
    
    // KPIs
    public $totalGastado;
    public $totalCompras;
    public $performance;

    // Tabla
    public $proveedores = [];

    // Detalle
    public $detalle = [];
    public $modalDetalle = false;
    public $proveedorNombre;

    public function mount()
    {
        // KPIs
        $this->totalGastado = DB::selectOne(
            "SELECT dashboard.fn_total_gastado() AS total"
        )->total;

        $this->totalCompras = DB::selectOne(
            "SELECT dashboard.fn_total_compras() AS total"
        )->total;

        $this->performance = DB::selectOne(
            "SELECT dashboard.fn_performance() AS total"
        )->total;

        // Compras por proveedor
        DB::beginTransaction();
        DB::statement("CALL dashboard.sp_compras_por_proveedor('cur_prov')");
        $this->proveedores = DB::select("FETCH ALL FROM cur_prov");
        DB::commit();
    }

    public function verDetalle($id, $nombre)
    {
        DB::beginTransaction();
        DB::statement(
            "CALL dashboard.sp_detalle_compras_proveedor(?, 'cur_det')",
            [$id]
        );
        $this->detalle = DB::select("FETCH ALL FROM cur_det");
        DB::commit();

        $this->proveedorNombre = $nombre;
        $this->modalDetalle = true;
    }

    public function cerrarDetalle()
    {
        $this->modalDetalle = false;
        $this->detalle = [];
    }

    public function render()
    {
        return view('livewire.reporte-compras');
    }
}
