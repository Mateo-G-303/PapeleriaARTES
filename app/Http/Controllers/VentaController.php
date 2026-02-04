<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['usuario', 'cliente', 'detalles.producto'])
            ->orderBy('idven', 'desc')
            ->get();
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $ivaActual = Configuracion::obtenerIva();
        return view('ventas.create', compact('ivaActual'));
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

    public function confirmarVenta(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.idpro' => 'required|exists:productos,idpro',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ]);

        // Calcular totales
        $subtotal = 0;
        foreach ($request->productos as $item) {
            $subtotal += $item['cantidad'] * $item['precio'];
        }

        $ivaActual = Configuracion::obtenerIva();
        $ivaValor = $subtotal * ($ivaActual / 100);
        $total = $subtotal + $ivaValor;

        return response()->json([
            'success' => true,
            'resumen' => [
                'subtotal' => number_format($subtotal, 2),
                'iva_porcentaje' => number_format($ivaActual, 2),
                'iva_valor' => number_format($ivaValor, 2),
                'total' => number_format($total, 2)
            ]
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->tienePermiso('ventas.crear')) {
            abort(403);
        }
        
        $request->validate([
            'tipo_factura' => 'required|in:factura,consumidor_final',
            'productos' => 'required|array|min:1',
            'productos.*.idpro' => 'required|exists:productos,idpro',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Obtener IVA actual
            $ivaActual = Configuracion::obtenerIva();

            // Calcular subtotal
            $subtotal = 0;
            foreach ($request->productos as $item) {
                $subtotal += $item['cantidad'] * $item['precio'];
            }

            // Calcular IVA y total
            $ivaValor = $subtotal * ($ivaActual / 100);
            $total = $subtotal + $ivaValor;

            // Determinar cliente
            if ($request->tipo_factura === 'consumidor_final') {
                $cliente = Cliente::consumidorFinal();
            } else {
                // Validar datos de factura
                $request->validate([
                    'cliente_ruc' => 'required|string|max:13',
                    'cliente_nombre' => 'required|string|max:255',
                    'cliente_direccion' => 'nullable|string|max:255',
                    'cliente_telefono' => 'nullable|string|max:20',
                    'cliente_email' => 'nullable|email|max:100',
                ]);

                $cliente = Cliente::updateOrCreate(
                    ['ruc_identificacion' => $request->cliente_ruc],
                    [
                        'nombre' => $request->cliente_nombre,
                        'direccion' => $request->cliente_direccion,
                        'telefono' => $request->cliente_telefono,
                        'email' => $request->cliente_email,
                    ]
                );
            }

            $venta = Venta::create([
                'user_id' => Auth::id(),
                'idcli' => $cliente->idcli,
                'fechaven' => now('America/Guayaquil'),
                'ivaven' => $ivaActual,
                'subtotalven' => $subtotal,
                'totalven' => $total
            ]);

            foreach ($request->productos as $item) {
                $producto = Producto::find($item['idpro']);

                if (!$producto->tieneStockDisponible($item['cantidad'])) {
                    throw new \Exception("Stock insuficiente para: {$producto->nombrepro}");
                }

                DetalleVenta::create([
                    'idven' => $venta->idven,
                    'idpro' => $producto->idpro,
                    'cantidaddven' => $item['cantidad'],
                    'preciounitariodven' => $item['precio'],
                    'subtotaldven' => $item['cantidad'] * $item['precio']
                ]);

                // Reducir stock
                $producto->stockpro -= $item['cantidad'];
                $producto->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada exitosamente',
                'venta_id' => $venta->idven
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $venta = Venta::with(['usuario', 'cliente', 'detalles.producto'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    public function generarPDF($id)
    {
        $venta = Venta::with(['usuario', 'cliente', 'detalles.producto'])->findOrFail($id);
        
        $pdf = Pdf::loadView('ventas.factura-pdf', compact('venta'));
        
        return $pdf->download("factura-{$venta->idven}.pdf");
    }

    public function imprimir($id)
    {
         $venta = Venta::with(['usuario', 'cliente', 'detalles.producto'])->findOrFail($id);
         return view('ventas.factura-pdf', compact('venta'));
    }
}