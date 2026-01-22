<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
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
    public $preciominpro;
    public $preciomaxpro;
    public $stockminpro;

    protected $rules = [
        'codbarraspro' => 'required|max:13',
        'nombrepro' => 'required|min:3',
        'idcat' => 'required',
        'precioventapro' => 'required|numeric',
        'stockpro' => 'required|integer',
        'preciocomprapro' => 'required|numeric',
        'preciominpro' => 'required|numeric',
        'preciomaxpro' => 'required|numeric',
        'stockminpro' => 'required|integer',
    ];

    public function render()
    {
        $productos = Producto::all();
        $categorias = Categoria::all();

        return view('livewire.productos', compact('productos', 'categorias'));
    }

    public function crear()
    {
        if (!auth()->user()->tienePermiso('productos.crear')) {
            abort(403);
        }
        $this->limpiarCampos();
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
        $this->preciominpro = $producto->preciominpro;
        $this->preciomaxpro = $producto->preciomaxpro;
        $this->stockminpro = $producto->stockminpro;

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
                'preciominpro' => $this->preciominpro,
                'preciomaxpro' => $this->preciomaxpro,
                'estadocatpro' => true,
                'preciocomprapro' => $this->preciocomprapro,
                'stockminpro' => $this->stockminpro
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
        Producto::find($id)->delete();
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
        $this->preciominpro = '';
        $this->preciomaxpro = '';
        $this->stockminpro = '';

        $this->modal = false;
    }

    public function exportarCSV()
    {
        $productos = DB::select('SELECT * FROM sp_exportar_todo_inventario()');

        $fileName = 'inventario_completo_' . date('Y-m-d_H-i') . '.csv';

        return response()->streamDownload(function () use ($productos) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'ID',
                'CÓDIGO BARRAS',
                'PRODUCTO',
                'CATEGORÍA',
                'COSTO COMPRA',
                'PRECIO VENTA',
                'PRECIO MÍNIMO',
                'PRECIO MÁXIMO',
                'STOCK ACTUAL',
                'STOCK ALERTA'
            ], ';');

            foreach ($productos as $row) {
                fputcsv($handle, [
                    $row->id_producto,
                    $row->codigo,
                    $row->nombre,
                    $row->categoria,
                    $row->costo_compra,
                    $row->precio_venta,
                    $row->precio_min,
                    $row->precio_max,
                    $row->stock_actual,
                    $row->alerta_stock
                ], ';');
            }

            fclose($handle);
        }, $fileName);
    }
}