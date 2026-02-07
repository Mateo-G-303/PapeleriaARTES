<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Productos extends Component
{
    public $id_producto_editar;
    public $modal = false;

    public $codbarraspro;
    public $nombrepro;
    public $idcat;
    public $precioventapro;
    public $stockpro;
    public $preciocomprapro;
    public $stockminpro;
    public $margenventa = 50;

    //Paginacion y buscador
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    protected $rules = [
        'codbarraspro' => 'required|max:13',
        'nombrepro' => 'required|min:3',
        'idcat' => 'required',
        'precioventapro' => 'required|numeric',
        'stockpro' => 'required|integer',
        'preciocomprapro' => 'required|numeric',
        'stockminpro' => 'required|integer',
        'margenventa' => 'required|numeric|min:0',
    ];
    public function updatedPreciocomprapro()
    {
        $this->calcularPrecioVenta();
    }

    public function updatedMargenventa()
    {
        $this->calcularPrecioVenta();
    }

    private function calcularPrecioVenta()
    {
        if ($this->preciocomprapro !== '' && $this->margenventa !== '') {
            $this->precioventapro =
                $this->preciocomprapro +
                ($this->preciocomprapro * $this->margenventa / 100);
        }
    }

    public function render()
    {
        $productos = Producto::with('categoria')
            ->where('activopro', true)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('codbarraspro', 'ILIKE', '%' . $this->search . '%')
                        ->orWhere('nombrepro', 'ILIKE', '%' . $this->search . '%')
                        ->orWhereHas('categoria', function ($c) {
                            $c->where('nombrecat', 'ILIKE', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy('nombrepro')
            ->paginate(1);

        $categorias = Categoria::where('activocat', true)
            ->orderBy('nombrecat')
            ->get();

        return view('livewire.productos', compact('productos', 'categorias'));
    }

    public function crear()
    {
        if (!auth()->user()->tienePermiso('productos.crear')) {
            abort(403);
        }
        $this->limpiarCampos();
        $this->search = '';
        $this->modal = true;
    }

    public function editar($id)
    {
        if (!auth()->user()->tienePermiso('productos.editar')) {
            abort(403);
        }
        $producto = Producto::find($id);

        $this->id_producto_editar = $producto->idpro;
        $this->codbarraspro = $producto->codbarraspro;
        $this->nombrepro = $producto->nombrepro;
        $this->idcat = $producto->idcat;
        $this->precioventapro = $producto->precioventapro;
        $this->stockpro = $producto->stockpro;
        $this->preciocomprapro = $producto->preciocomprapro;
        $this->stockminpro = $producto->stockminpro;
        $this->margenventa = $producto->margenventa;

        $this->modal = true;
    }

    public function guardar()
    {
        $permiso = $this->id_producto_editar ? 'productos.editar' : 'productos.crear';
        if (!auth()->user()->tienePermiso($permiso)) {
            abort(403);
        }

        $this->validate();

        Producto::updateOrCreate(
            ['idpro' => $this->id_producto_editar],
            [
                'codbarraspro' => $this->codbarraspro,
                'nombrepro' => $this->nombrepro,
                'idcat' => $this->idcat,
                'precioventapro' => $this->precioventapro,
                'stockpro' => $this->stockpro,
                'margenventa' => $this->margenventa,
                'estadocatpro' => true,
                'preciocomprapro' => $this->preciocomprapro,
                'stockminpro' => $this->stockminpro,
                'activopro' => true
            ]
        );

        $this->modal = false;
        $this->limpiarCampos();
    }

    public function borrar($id)
    {
        if (!auth()->user()->tienePermiso('productos.eliminar')) {
            abort(403);
        }
        //ELiminado Logico
        $producto = Producto::findOrFail($id);

        if (!$producto->activopro) {
            return;
        }

        $producto->activopro = false;
        $producto->stockpro  = 0;
        $producto->save();
    }

    public function limpiarCampos()
    {
        $this->id_producto_editar = null;
        $this->codbarraspro = '';
        $this->nombrepro = '';
        $this->idcat = '';
        $this->precioventapro = '';
        $this->stockpro = '';
        $this->preciocomprapro = '';
        $this->stockminpro = '';
        $this->margenventa = 50;

        $this->modal = false;
    }

    public function exportarCSV()
    {
        // 1. Ejecutamos el procedimiento almacenado
        $productos = DB::select('SELECT * FROM sp_exportar_todo_inventario()');

        $fileName = 'inventario_valorizado_' . date('Y-m-d_H-i') . '.csv';

        return response()->streamDownload(function () use ($productos) {
            $handle = fopen('php://output', 'w');

            // BOM para que Excel reconozca tildes y ñ correctamente
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // 2. Encabezados actualizados
            fputcsv($handle, [
                'ID',
                'CÓDIGO BARRAS',
                'PRODUCTO',
                'CATEGORÍA',
                'COSTO COMPRA',
                'MARGEN (%)',
                'PRECIO VENTA',
                'STOCK ACTUAL',
                'STOCK MÍNIMO',
                'ESTADO',
                'VALOR TOTAL ($)' // Nueva columna calculada
            ], ';');

            $granTotalDinero = 0;
            $totalItems = 0;

            // 3. Recorremos los productos
            foreach ($productos as $row) {
                // Sumamos para el reporte final
                $granTotalDinero += $row->valor_total_producto;
                $totalItems++;

                fputcsv($handle, [
                    $row->id_producto,
                    $row->codigo,
                    $row->nombre,
                    $row->categoria,
                    $row->costo_compra,
                    $row->margen_venta,
                    $row->precio_venta,
                    $row->stock_actual,
                    $row->stock_minimo,
                    $row->estado,
                    $row->valor_total_producto // Dato calculado en SQL
                ], ';');
            }

            // 4. (Opcional) Agregar una fila vacía y luego los totales al final
            fputcsv($handle, [], ';'); // Fila vacía separadora
            fputcsv($handle, [
                '',
                '',
                'TOTALES GENERALES:',
                '',
                '',
                '',
                '',
                $totalItems . ' Productos',
                '',
                '',
                number_format($granTotalDinero, 2, '.', '') // Total de dinero invertido
            ], ';');

            fclose($handle);
        }, $fileName);
    }
}
