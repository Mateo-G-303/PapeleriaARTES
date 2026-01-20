<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['usuario', 'detalles.producto'])
            ->orderBy('idven', 'desc')
            ->get();
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        return view('ventas.create');
    }

    public function buscarProducto(Request $request)
    {
        $codigo = $request->codigo;
        
        $producto = Producto::where('codbarraspro', $codigo)
            ->where('estadocatpro', true)
            ->first();

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ]);
        }

        $stockDisponible = $producto->stockDisponibleParaVenta();

        if ($stockDisponible <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Producto sin stock disponible (stock mínimo alcanzado)'
            ]);
        }

        return response()->json([
            'success' => true,
            'producto' => [
                'idpro' => $producto->idpro,
                'codigo' => $producto->codbarraspro,
                'nombre' => $producto->nombrepro,
                'precio' => $producto->precioventapro,
                'stock' => $producto->stockpro,
                'stockMinimo' => $producto->stockminpro,
                'stockDisponible' => $stockDisponible
            ]
        ]);
    }

    /**
     * Guardar venta usando transacciones (Maestro-Detalle)
     */
    public function store(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.idpro' => 'required|exists:productos,idpro',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'iva_porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        // Iniciar transacción
        DB::beginTransaction();

        try {
            // Calcular subtotal
            $subtotal = 0;
            foreach ($request->productos as $item) {
                $subtotal += $item['cantidad'] * $item['precio'];
            }

            // Calcular IVA y total
            $ivaPorcentaje = $request->iva_porcentaje;
            $ivaValor = $subtotal * ($ivaPorcentaje / 100);
            $total = $subtotal + $ivaValor;

            // ==========================================
            // CREAR VENTA (MAESTRO/CABECERA)
            // ==========================================
            $venta = Venta::create([
                'user_id' => Auth::id(),
                'fechaven' => now(),
                'subtotalven' => $subtotal,
                'ivaven' => $ivaPorcentaje,
                'totalven' => $total
            ]);

            // ==========================================
            // CREAR DETALLES Y ACTUALIZAR STOCK
            // ==========================================
            foreach ($request->productos as $detalle) {
                $producto = Producto::find($detalle['idpro']);

                // Verificar stock disponible
                if (!$producto->tieneStockDisponible($detalle['cantidad'])) {
                    throw new \Exception(
                        "Stock insuficiente para: {$producto->nombrepro}. " .
                        "Disponible: {$producto->stockDisponibleParaVenta()}"
                    );
                }

                // Crear detalle de venta
                DetalleVenta::create([
                    'idven' => $venta->idven,
                    'idpro' => $detalle['idpro'],
                    'cantidaddven' => $detalle['cantidad'],
                    'preciounitariodven' => $detalle['precio'],
                    'subtotaldven' => $detalle['cantidad'] * $detalle['precio']
                ]);

                // Reducir stock del producto
                $producto->decrement('stockpro', $detalle['cantidad']);
            }

            // ==========================================
            // CONFIRMAR TRANSACCIÓN
            // ==========================================
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada correctamente',
                'venta_id' => $venta->idven,
                'total' => $total
            ]);

        } catch (\Exception $e) {
            // ==========================================
            // REVERTIR TRANSACCIÓN EN CASO DE ERROR
            // ==========================================
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        $venta = Venta::with(['usuario', 'detalles.producto'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    public function generarFactura($id)
    {
        $venta = Venta::with(['usuario', 'detalles.producto'])->findOrFail($id);
        
        $pdf = Pdf::loadView('ventas.factura', compact('venta'));
        
        return $pdf->download("factura-{$venta->idven}.pdf");
    }

    public function verFactura($id)
    {
        $venta = Venta::with(['usuario', 'detalles.producto'])->findOrFail($id);
        
        $pdf = Pdf::loadView('ventas.factura', compact('venta'));
        
        return $pdf->stream("factura-{$venta->idven}.pdf");
    }
}