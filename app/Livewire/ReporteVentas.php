<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\DB;

class ReporteVentas extends Component
{
    public $tipoReporte = 'dia';
    public $fechaInicio;
    public $fechaFin;
    public $filtroProductos = 'mas';
    
    // KPIs
    public $totalVentas = 0;
    public $cantidadVentas = 0;
    public $promedioVenta = 0;
    public $totalSemana = 0;
    
    // Datos
    public $ventas = [];
    public $productosVendidos = [];
    public $clientesFrecuentes = [];
    public $clientesMayorVolumen = [];
    
    // Comparativo
    public $ventasMesActual = 0;
    public $ventasMesAnterior = 0;
    public $porcentajeCambio = 0;
    public $diferenciaDinero = 0;
    
    // Para gráficos
    public $chartLabels = [];
    public $chartData = [];
    public $chartLabelsSemana = [];
    public $chartDataSemana = [];

    public function mount()
    {
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
        $this->cargarReporte();
    }

    public function cambiarReporte($tipo)
    {
        $this->tipoReporte = $tipo;
        $this->cargarReporte();
    }

    public function buscar()
    {
        $this->cargarReporte();
    }

    public function cargarReporte()
    {
        $this->resetData();

        switch ($this->tipoReporte) {
            case 'dia':
                $this->reporteDiaSemana();
                break;
            case 'rango':
                $this->reporteRango();
                break;
            case 'comparativo':
                $this->reporteComparativo();
                break;
            case 'clientes-frecuentes':
                $this->reporteClientesFrecuentes();
                break;
            case 'clientes-volumen':
                $this->reporteClientesMayorVolumen();
                break;
        }
    }

    private function resetData()
    {
        $this->chartLabels = [];
        $this->chartData = [];
        $this->chartLabelsSemana = [];
        $this->chartDataSemana = [];
        $this->ventas = [];
        $this->productosVendidos = [];
        $this->totalVentas = 0;
        $this->cantidadVentas = 0;
        $this->promedioVenta = 0;
        $this->totalSemana = 0;
    }

    public function reporteDiaSemana()
    {
        $hoy = now('America/Guayaquil')->toDateString();
        
        // Ventas del día
        $this->ventas = Venta::with(['usuario', 'cliente', 'detalles.producto'])
            ->whereDate('fechaven', $hoy)
            ->orderBy('fechaven', 'desc')
            ->get()
            ->toArray();
        
        $this->calcularKPIs($this->ventas);
        
        // Gráfico por horario
        $rangos = [
            '7:00-9:00' => [7, 9],
            '9:00-12:00' => [9, 12],
            '12:00-15:00' => [12, 15],
            '15:00-19:00' => [15, 19],
        ];
        
        $this->chartLabels = array_keys($rangos);
        $this->chartData = [];
        
        foreach ($rangos as $horas) {
            $cantidad = Venta::whereDate('fechaven', $hoy)
                ->whereRaw('EXTRACT(HOUR FROM fechaven) >= ?', [$horas[0]])
                ->whereRaw('EXTRACT(HOUR FROM fechaven) < ?', [$horas[1]])
                ->count();
            $this->chartData[] = $cantidad;
        }
        
        // Gráfico de la semana
        $inicioSemana = now('America/Guayaquil')->startOfWeek();
        $dias = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        $montos = [];
        
        for ($i = 0; $i < 7; $i++) {
            $fecha = $inicioSemana->copy()->addDays($i)->toDateString();
            $montos[] = (float) Venta::whereDate('fechaven', $fecha)->sum('totalven');
        }
        
        $this->chartLabelsSemana = $dias;
        $this->chartDataSemana = $montos;
        $this->totalSemana = array_sum($montos);
    }

    public function reporteRango()
    {
        $ventasRango = Venta::whereDate('fechaven', '>=', $this->fechaInicio)
            ->whereDate('fechaven', '<=', $this->fechaFin)
            ->get()
            ->toArray();
        
        $this->calcularKPIs($ventasRango);
        
        if ($this->filtroProductos === 'ganancia') {
            $this->productosVendidos = DetalleVenta::select(
                    'productos.idpro',
                    'productos.nombrepro',
                    'productos.codbarraspro',
                    DB::raw('SUM(detalleventas.cantidaddven) as total_vendido'),
                    DB::raw('SUM(detalleventas.subtotaldven) as total_ingresos')
                )
                ->join('productos', 'detalleventas.idpro', '=', 'productos.idpro')
                ->join('ventas', 'detalleventas.idven', '=', 'ventas.idven')
                ->whereDate('ventas.fechaven', '>=', $this->fechaInicio)
                ->whereDate('ventas.fechaven', '<=', $this->fechaFin)
                ->groupBy('productos.idpro', 'productos.nombrepro', 'productos.codbarraspro')
                ->orderBy('total_ingresos', 'desc')
                ->get()
                ->toArray();
        } else {
            $orden = $this->filtroProductos === 'mas' ? 'desc' : 'asc';
            
            $this->productosVendidos = DetalleVenta::select(
                    'productos.idpro',
                    'productos.nombrepro',
                    'productos.codbarraspro',
                    DB::raw('SUM(detalleventas.cantidaddven) as total_vendido'),
                    DB::raw('SUM(detalleventas.subtotaldven) as total_ingresos')
                )
                ->join('productos', 'detalleventas.idpro', '=', 'productos.idpro')
                ->join('ventas', 'detalleventas.idven', '=', 'ventas.idven')
                ->whereDate('ventas.fechaven', '>=', $this->fechaInicio)
                ->whereDate('ventas.fechaven', '<=', $this->fechaFin)
                ->groupBy('productos.idpro', 'productos.nombrepro', 'productos.codbarraspro')
                ->orderBy('total_vendido', $orden)
                ->get()
                ->toArray();
        }
    }

    public function reporteComparativo()
    {
        $inicioMesActual = now('America/Guayaquil')->startOfMonth();
        $finMesActual = now('America/Guayaquil')->endOfMonth();
        $inicioMesAnterior = now('America/Guayaquil')->subMonth()->startOfMonth();
        $finMesAnterior = now('America/Guayaquil')->subMonth()->endOfMonth();
        
        $this->ventasMesActual = (float) Venta::whereDate('fechaven', '>=', $inicioMesActual)
            ->whereDate('fechaven', '<=', $finMesActual)
            ->sum('totalven');
        
        $this->ventasMesAnterior = (float) Venta::whereDate('fechaven', '>=', $inicioMesAnterior)
            ->whereDate('fechaven', '<=', $finMesAnterior)
            ->sum('totalven');
        
        $this->diferenciaDinero = $this->ventasMesActual - $this->ventasMesAnterior;
        
        if ($this->ventasMesAnterior > 0) {
            $this->porcentajeCambio = (($this->ventasMesActual - $this->ventasMesAnterior) / $this->ventasMesAnterior) * 100;
        } else {
            $this->porcentajeCambio = $this->ventasMesActual > 0 ? 100 : 0;
        }
    }

    public function reporteClientesFrecuentes()
    {
        $this->clientesFrecuentes = Venta::select(
                'clientes.idcli',
                'clientes.nombre',
                'clientes.ruc_identificacion',
                DB::raw('COUNT(ventas.idven) as total_compras'),
                DB::raw('SUM(ventas.totalven) as total_gastado')
            )
            ->join('clientes', 'ventas.idcli', '=', 'clientes.idcli')
            ->where('clientes.es_consumidor_final', false)
            ->groupBy('clientes.idcli', 'clientes.nombre', 'clientes.ruc_identificacion')
            ->orderBy('total_compras', 'desc')
            ->get()
            ->toArray();
    }

    public function reporteClientesMayorVolumen()
    {
        $this->clientesMayorVolumen = Venta::select(
                'clientes.idcli',
                'clientes.nombre',
                'clientes.ruc_identificacion',
                DB::raw('COUNT(ventas.idven) as total_compras'),
                DB::raw('SUM(ventas.totalven) as total_gastado')
            )
            ->join('clientes', 'ventas.idcli', '=', 'clientes.idcli')
            ->where('clientes.es_consumidor_final', false)
            ->groupBy('clientes.idcli', 'clientes.nombre', 'clientes.ruc_identificacion')
            ->orderBy('total_gastado', 'desc')
            ->get()
            ->toArray();
    }

    private function calcularKPIs($ventas)
    {
        $this->totalVentas = array_sum(array_column($ventas, 'totalven'));
        $this->cantidadVentas = count($ventas);
        $this->promedioVenta = $this->cantidadVentas > 0 ? $this->totalVentas / $this->cantidadVentas : 0;
    }

    public function render()
    {
        return view('livewire.reporte-ventas');
    }
}