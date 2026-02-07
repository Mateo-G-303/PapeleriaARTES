<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class Compras extends Component
{
    public $proveedores;
    public $productos;

    public $idprov;
    public $idpro;
    public $cantidaddet;
    public $costototal;          // NUEVO
    public $preciounitario;      // CALCULADO

    public $detalles = [];
    public $modal = false;

    public $margen = 50;
    //Para detalles
    public $detalleCompra = [];
    public $modalDetalle = false;
    public $codigoCompra;
    public $proveedorCompra;

    public $errorProveedor = null;

    //Para buscar
    public $search = '';
    //Paginacion
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public function updatedSearch()
    {
        $this->resetPage();
    }


    public function mount()
    {
        $this->proveedores = Proveedor::where('activoprov', true)
        ->orderBy('nombreprov')
        ->get();
        $this->productos = Producto::where('activopro', true)
        ->orderBy('nombrepro')
        ->get();

    }

    public function abrirModal()
    {
        $this->proveedores = Proveedor::where('activoprov', true)->get();
        $this->productos   = Producto::where('activopro', true)->get();


        $this->search ='';
        $this->modal = true;
        $this->errorProveedor = null;
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'idprov',
            'idpro',
            'cantidaddet',
            'costototal',
            'preciounitario',
            'detalles'
        ]);
    }

    /* ===============================
       AGREGAR DETALLE (SIN TIEMPO REAL)
       =============================== */

    public function agregarDetalle()
    {
        if (
            !$this->idprov ||
            !$this->idpro ||
            !$this->cantidaddet ||
            !$this->costototal
        ) {
            return;
        }

        $cantidad = (float) $this->cantidaddet;
        $costo    = (float) $this->costototal;

        if ($cantidad <= 0 || $costo <= 0) {
            return;
        }
        // VALIDAR QUE EL PROVEEDOR SEA EL MISMO
        if (count($this->detalles) > 0) {
            $proveedorActual = $this->detalles[0]['idprov'];

            if ($proveedorActual != $this->idprov) {
                $this->errorProveedor =
                    'No puedes agregar productos de diferentes proveedores en una misma compra.';
                return;
            }
        }

        $this->errorProveedor = null;

        $preciounitario = round($costo / $cantidad, 4);

        $producto = Producto::find($this->idpro);
        $margen   = $this->margen ?? 50;
        $factor   = $margen / 100;

        $precioventa = round(
            $preciounitario + ($preciounitario * $factor),
            2
        );

        $proveedor = Proveedor::find($this->idprov);
        $producto  = Producto::find($this->idpro);

        $this->detalles[] = [
            'idprov'         => $this->idprov,
            'proveedor'      => $proveedor?->nombreprov,
            'idpro'          => $this->idpro,
            'producto'       => $producto?->nombrepro,
            'costototalpaquete'     => $costo,
            'cantidaddet'    => $cantidad,
            'preciounitario' => $preciounitario,
            'margenventa'    => $margen,
            'precioventa'    => $precioventa,
        ];

        $this->reset([
            'idpro',
            'cantidaddet',
            'costototal'
        ]);
    }

    public function eliminarDetalle($index)
    {
        unset($this->detalles[$index]);
        $this->detalles = array_values($this->detalles);
        
    }

    /* ===============================
       GUARDAR COMPRA
       =============================== */

    public function guardar()
    {
        if (!$this->idprov || count($this->detalles) === 0) {
            return;
        }
        $proveedores = collect($this->detalles)
            ->pluck('idprov')
            ->unique();

        if ($proveedores->count() > 1) {
            $this->errorProveedor = 'No se puede guardar la compra con múltiples proveedores.';
            return;
        }

        $this->errorProveedor = null;

        DB::transaction(function () {

            $total = collect($this->detalles)->sum('costototalpaquete');

            $compra = Compra::create([
                'idprov'        => $this->idprov,
                'fechacom'      => now(),
                'montototalcom' => $total,
            ]);

            foreach ($this->detalles as $d) {

                DetalleCompra::create([
                    'idcom'               => $compra->idcom,
                    'idpro'               => $d['idpro'],
                    'cantidaddet'         => $d['cantidaddet'],
                    'preciounitario'      => $d['preciounitario'],
                    'costototalpaquete'   => $d['costototalpaquete'],
                    'margenaplicadodet'   => $d['margenventa'],
                ]);

                $producto = Producto::find($d['idpro']);

                if ($producto) {
                    $producto->stockpro += $d['cantidaddet'];
                    $producto->preciocomprapro = $d['preciounitario'];
                    $producto->margenventa = $d['margenventa'];
                    $margen = $producto->margenventa / 100;
                    $producto->precioventapro = $d['preciounitario'] + ($d['preciounitario'] * $margen);
                    $producto->save();
                }
            }
        });

        $this->cerrarModal();

        
    }
    public function verDetalle($idcom)
    {
        $compra = Compra::with(['proveedor', 'detalles.producto'])
            ->findOrFail($idcom);

        $this->codigoCompra    = $compra->codigocom;
        $this->proveedorCompra = $compra->proveedor->nombreprov;
        $this->detalleCompra   = $compra->detalles;

        $this->modalDetalle = true;
    }
    public function cerrarDetalle()
    {
        $this->modalDetalle = false;
        $this->detalleCompra = [];
    }

    public function render()
    {
        $compras = Compra::with('proveedor')
        ->when($this->search, function ($query) {
            $query->where(function ($q) {

                $q->where('codigocom', 'ILIKE', '%' . $this->search . '%')
                  ->orWhereHas('proveedor', function ($p) {
                      $p->where('nombreprov', 'ILIKE', '%' . $this->search . '%');
                  })

                ->orWhereRaw('fechacom::text ILIKE ?', ['%' . $this->search . '%']);
            });
        })
        ->orderBy('idcom', 'desc')
        ->paginate(5);
        return view('livewire.compras', compact('compras'));
    }
}
