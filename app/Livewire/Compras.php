<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class Compras extends Component
{
    public $proveedores;
    public $productos;

    public $idprov;
    public $idpro;
    public $cantidaddetc;
    public $preciocompra;
    public $compras;
    public $detalles = [];
    public $modal = false;

    public function abrirModal()
    {
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }
    public function mount()
    {
        $this->proveedores = Proveedor::all();
        $this->productos = Producto::all();
        $this->compras = Compra::with('proveedor')->orderBy('idcom', 'desc')->get();
    }

    public function agregarDetalle()
    {
        $this->detalles[] = [
            'idpro' => $this->idpro,
            'cantidaddetc' => $this->cantidaddetc,
            'preciocompra' => $this->preciocompra,
        ];

        $this->reset(['idpro', 'cantidaddetc', 'preciocompra']);
    }

    public function guardar()
    {
    if (!$this->idprov || count($this->detalles) === 0) {
        return;
    }

    DB::transaction(function () {

        // 1️⃣ Crear compra
        $compra = Compra::create([
            'idprov' => $this->idprov,
            'codigocom' => 'COM-' . time(),
            'fechacom' => now(),
            'montototalcom' => collect($this->detalles)
                ->sum(fn ($d) => $d['cantidaddetc'] * $d['preciocompra']),
        ]);

        // 2️⃣ Detalles + actualización de productos
        foreach ($this->detalles as $d) {

            // Guardar detalle
            DetalleCompra::create([
                'idcom' => $compra->idcom,
                'idpro' => $d['idpro'],
                'cantidaddetc' => $d['cantidaddetc'],
                'preciocompra' => $d['preciocompra'],
            ]);

            // 🔥 ACTUALIZAR PRODUCTO
            $producto = Producto::find($d['idpro']);

            if ($producto) {
    // Aumentar stock
                $producto->stockpro += $d['cantidaddetc'];

    // Precio de compra
                $producto->preciocomprapro = $d['preciocompra'];

                // Precio de venta (ejemplo: 30% de margen)
                $producto->precioventapro = $d['preciocompra'] * 1;

                $producto->save();
            }
        }
    });

    // Limpiar estado
    $this->cerrarModal();

    $this->reset([
        'idprov',
        'idpro',
        'cantidaddetc',
        'preciocompra',
        'detalles'
    ]);

    // Recargar compras
    $this->compras = Compra::with('proveedor')
        ->orderBy('idcom', 'desc')
        ->get();
    }

    public function render()
    {
        return view('livewire.compras');
    }
    public function eliminarDetalle($index)
    {
        unset($this->detalles[$index]);
        $this->detalles = array_values($this->detalles);
    }
    
}