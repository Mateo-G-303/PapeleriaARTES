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
        if (!auth()->user()->tienePermiso('ventas.ver')) {
            abort(403);
        }
        
        $ventas = Venta::with(['usuario', 'detalles.producto'])
            ->orderBy('idven', 'desc')
            ->get();
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        if (!auth()->user()->tienePermiso('ventas.crear')) {
            abort(403);
        }
        
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

    public function store(Request $request)
    {
        if (!auth()->user()->tienePermiso('ventas.crear')) {
            abort(403);
        }
        
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.idpro' => 'required|exists:productos,idpro',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;
            foreach ($request->productos as $item) {
                $total += $item['cantidad'] * $item['precio'];
            }

            $venta = Venta::create([
                'user_id' => Auth::id(),
                'fechaven' => now(),
                'totalven' => $total
            ]);

            foreach ($request->productos as $item) {
                $producto = Producto::find($item['idpro']);

                if (!$producto->tieneStockDisponible($item['cantidad'])) {
                    throw new \Exception("Stock insuficiente para: {$producto->nombrepro}. Disponible: {$producto->stockDisponibleParaVenta()}");
                }

                DetalleVenta::create([
                    'idven' => $venta->idven,
                    'idpro' => $item['idpro'],
                    'cantidaddven' => $item['cantidad'],
                    'preciounitariodven' => $item['precio'],
                    'subtotaldven' => $item['cantidad'] * $item['precio']
                ]);

                $producto->decrement('stockpro', $item['cantidad']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada correctamente',
                'venta_id' => $venta->idven
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        if (!auth()->user()->tienePermiso('ventas.ver')) {
            abort(403);
        }
        
        $venta = Venta::with(['usuario', 'detalles.producto'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    public function generarFactura($id)
    {
        if (!auth()->user()->tienePermiso('ventas.facturar')) {
            abort(403);
        }
        
        $venta = Venta::with(['usuario', 'detalles.producto'])->findOrFail($id);
        
        $pdf = Pdf::loadView('ventas.factura', compact('venta'));
        
        return $pdf->download("factura-{$venta->idven}.pdf");
    }

    public function verFactura($id)
    {
        if (!auth()->user()->tienePermiso('ventas.facturar')) {
            abort(403);
        }
        
        $venta = Venta::with(['usuario', 'detalles.producto'])->findOrFail($id);
        
        $pdf = Pdf::loadView('ventas.factura', compact('venta'));
        
        return $pdf->stream("factura-{$venta->idven}.pdf");
    }
}