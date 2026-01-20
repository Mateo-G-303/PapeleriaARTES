<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB; // <-- No olvides importar esto arriba
use Symfony\Component\HttpFoundation\StreamedResponse; // <-- Y esto también

class Productos extends Component
{
    // Variables para el Formulario
    public $id_producto_editar; // Para saber si estamos editando
    public $modal = false;      // Controla si la ventana está abierta

    // Campos de la base de datos
    public $codbarraspro;
    public $nombrepro;
    public $idcat;
    public $precioventapro;
    public $stockpro;
    public $preciocomprapro;
    public $preciominpro;
    public $preciomaxpro;
    public $stockminpro;
    // (Puedes añadir más campos como preciocompra, stockmin, etc. aquí)

    // Reglas de validación
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
        $categorias = Categoria::all(); // Necesario para el <select> del formulario

        return view('livewire.productos', compact('productos', 'categorias'));
    }

    // Abrir Modal para CREAR
    public function crear()
    {
        $this->limpiarCampos();
        $this->modal = true;
    }

    // Abrir Modal para EDITAR
    public function editar($id)
    {
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

    // Guardar o Actualizar
    public function guardar()
    {
        $this->validate();

        Producto::updateOrCreate(
            ['idpro' => $this->id_producto_editar], // Si existe este ID, actualiza
            [
                'codbarraspro' => $this->codbarraspro,
                'nombrepro' => $this->nombrepro,
                'idcat' => $this->idcat,
                'precioventapro' => $this->precioventapro,
                'stockpro' => $this->stockpro,
                // Valores por defecto para lo que no pedimos en el form:
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

    // Borrar Producto
    public function borrar($id)
    {
        Producto::find($id)->delete();
    }

    // Cerrar Modal y limpiar
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
        // 1. Llamamos al procedimiento almacenado NUEVO
        $productos = DB::select('SELECT * FROM sp_exportar_todo_inventario()');

        $fileName = 'inventario_completo_' . date('Y-m-d_H-i') . '.csv';

        return response()->streamDownload(function () use ($productos) {
            $handle = fopen('php://output', 'w');

            // BOM para tildes en Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // 2. Encabezados COMPLETOS
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

            // 3. Escribir TODOS los datos
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
