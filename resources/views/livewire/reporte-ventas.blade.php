<div id="reportes-ventas-container"
    data-tipo="{{ $tipoReporte }}"
    data-labels='@json($chartLabels)'
    data-valores='@json($chartData)'
    data-filtro="{{ $filtroProductos ?? 'mas' }}">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reportes de Ventas</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Análisis completo del rendimiento de ventas</p>
        </div>
        <a href="{{ route('reportes.index') }}" style="background-color: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">
            ← Volver
        </a>
    </div>

    {{-- Menú de reportes --}}
    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; background-color: #f3f4f6; padding: 1rem; border-radius: 0.75rem;" class="dark:bg-zinc-800">
        <button wire:click="cambiarReporte('dia')" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; font-weight: 600; {{ $tipoReporte == 'dia' ? 'background-color: #7c3aed; color: white;' : 'background-color: white; color: #374151;' }}">
            📅 Ventas del Día / Semana
        </button>
        <button wire:click="cambiarReporte('rango')" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; font-weight: 600; {{ $tipoReporte == 'rango' ? 'background-color: #7c3aed; color: white;' : 'background-color: white; color: #374151;' }}">
            🔍 Por Rango de Fechas
        </button>
        <button wire:click="cambiarReporte('comparativo')" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; font-weight: 600; {{ $tipoReporte == 'comparativo' ? 'background-color: #7c3aed; color: white;' : 'background-color: white; color: #374151;' }}">
            📊 Comparativo
        </button>
        <button wire:click="cambiarReporte('clientes-frecuentes')" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; font-weight: 600; {{ $tipoReporte == 'clientes-frecuentes' ? 'background-color: #0891b2; color: white;' : 'background-color: white; color: #374151;' }}">
            👥 Clientes Frecuentes
        </button>
        <button wire:click="cambiarReporte('clientes-volumen')" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; font-weight: 600; {{ $tipoReporte == 'clientes-volumen' ? 'background-color: #7c3aed; color: white;' : 'background-color: white; color: #374151;' }}">
            💰 Mayor Volumen
        </button>
    </div>

    {{-- ==================== VENTAS DEL DÍA + SEMANA (UNIFICADO) ==================== --}}
    @if($tipoReporte == 'dia')
    
    {{-- KPIs del Día --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background-color: #dbeafe; padding: 1.25rem; border-radius: 0.75rem; text-align: center; border: 2px solid #3b82f6;">
            <p style="color: #1e40af; font-weight: 600; font-size: 0.875rem;">Total Ventas Hoy</p>
            <p style="color: #1e3a8a; font-size: 1.75rem; font-weight: 700;">${{ number_format($totalVentas, 2) }}</p>
        </div>
        <div style="background-color: #d1fae5; padding: 1.25rem; border-radius: 0.75rem; text-align: center; border: 2px solid #10b981;">
            <p style="color: #065f46; font-weight: 600; font-size: 0.875rem;">Cantidad de Ventas</p>
            <p style="color: #064e3b; font-size: 1.75rem; font-weight: 700;">{{ $cantidadVentas }}</p>
        </div>
        <div style="background-color: #fef3c7; padding: 1.25rem; border-radius: 0.75rem; text-align: center; border: 2px solid #f59e0b;">
            <p style="color: #92400e; font-weight: 600; font-size: 0.875rem;">Promedio por Venta</p>
            <p style="color: #78350f; font-size: 1.75rem; font-weight: 700;">${{ number_format($promedioVenta, 2) }}</p>
        </div>
    </div>

    {{-- Tabla de ventas del día + Gráfico por horario --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;" class="max-lg:grid-cols-1">
        {{-- Tabla de ventas --}}
        <div style="background-color: white; border: 2px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden;" class="dark:bg-zinc-800 dark:border-zinc-600">
            <div style="background-color: #7c3aed; color: white; padding: 0.75rem 1rem;">
                <h3 style="font-weight: 600;">📅 Detalle de Ventas de Hoy</h3>
            </div>
            <div style="max-height: 350px; overflow-y: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: #f3f4f6; position: sticky; top: 0;" class="dark:bg-zinc-700">
                        <tr>
                            <th style="padding: 0.5rem; text-align: left; font-size: 0.75rem; color: #374151;" class="dark:text-gray-200">#</th>
                            <th style="padding: 0.5rem; text-align: left; font-size: 0.75rem; color: #374151;" class="dark:text-gray-200">Hora</th>
                            <th style="padding: 0.5rem; text-align: left; font-size: 0.75rem; color: #374151;" class="dark:text-gray-200">Cliente</th>
                            <th style="padding: 0.5rem; text-align: right; font-size: 0.75rem; color: #374151;" class="dark:text-gray-200">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventas as $venta)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 0.5rem; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">{{ $venta['idven'] }}</td>
                            <td style="padding: 0.5rem; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">{{ \Carbon\Carbon::parse($venta['fechaven'])->format('H:i') }}</td>
                            <td style="padding: 0.5rem; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">{{ Str::limit($venta['cliente']['nombre'] ?? 'C.F.', 20) }}</td>
                            <td style="padding: 0.5rem; font-size: 0.875rem; text-align: right; font-weight: 600; color: #16a34a;">${{ number_format($venta['totalven'], 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="padding: 2rem; text-align: center; color: #6b7280;">Sin ventas hoy</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Gráfico por horario --}}
        <div style="background-color: white; border: 2px solid #e5e7eb; border-radius: 0.75rem; padding: 1rem;" class="dark:bg-zinc-800 dark:border-zinc-600">
            <h3 style="font-weight: 600; margin-bottom: 1rem; color: #374151;" class="dark:text-gray-200">Clientes por Horario (Hoy)</h3>
            <div style="position: relative; min-height: 280px;">
                <canvas id="graficoHorario"></canvas>
            </div>
        </div>
    </div>

    {{-- Sección Ventas de la Semana --}}
    <div style="background-color: white; border: 2px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden;" class="dark:bg-zinc-800 dark:border-zinc-600">
        <div style="background-color: #3b82f6; color: white; padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 600;">📆 Ventas de la Semana</h3>
            <span style="background-color: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-weight: 700;">
                Total: ${{ number_format($totalSemana, 2) }}
            </span>
        </div>
        <div style="padding: 1rem;">
            <div style="position: relative; min-height: 300px;">
                <canvas id="graficoSemana"></canvas>
            </div>
        </div>
    </div>
    @endif

    {{-- ==================== POR RANGO DE FECHAS ==================== --}}
    @if($tipoReporte == 'rango')
    
    {{-- KPIs --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background-color: #dbeafe; padding: 1.25rem; border-radius: 0.75rem; text-align: center; border: 2px solid #3b82f6;">
            <p style="color: #1e40af; font-weight: 600; font-size: 0.875rem;">Total Ventas</p>
            <p style="color: #1e3a8a; font-size: 1.75rem; font-weight: 700;">${{ number_format($totalVentas, 2) }}</p>
        </div>
        <div style="background-color: #d1fae5; padding: 1.25rem; border-radius: 0.75rem; text-align: center; border: 2px solid #10b981;">
            <p style="color: #065f46; font-weight: 600; font-size: 0.875rem;">Cantidad de Ventas</p>
            <p style="color: #064e3b; font-size: 1.75rem; font-weight: 700;">{{ $cantidadVentas }}</p>
        </div>
        <div style="background-color: #fef3c7; padding: 1.25rem; border-radius: 0.75rem; text-align: center; border: 2px solid #f59e0b;">
            <p style="color: #92400e; font-weight: 600; font-size: 0.875rem;">Promedio por Venta</p>
            <p style="color: #78350f; font-size: 1.75rem; font-weight: 700;">${{ number_format($promedioVenta, 2) }}</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div style="background-color: #f3f4f6; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;" class="dark:bg-zinc-800">
        <div>
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem;" class="dark:text-gray-200">Fecha Inicio</label>
            <input type="date" wire:model="fechaInicio" style="padding: 0.5rem; border: 2px solid #d1d5db; border-radius: 0.5rem;">
        </div>
        <div>
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem;" class="dark:text-gray-200">Fecha Fin</label>
            <input type="date" wire:model="fechaFin" style="padding: 0.5rem; border: 2px solid #d1d5db; border-radius: 0.5rem;">
        </div>
        <div>
            <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem;" class="dark:text-gray-200">Ordenar por</label>
            <select wire:model="filtroProductos" style="padding: 0.5rem; border: 2px solid #d1d5db; border-radius: 0.5rem;">
                <option value="mas">Más Vendidos (Cantidad)</option>
                <option value="menos">Menos Vendidos (Cantidad)</option>
                <option value="ganancia">Mayor Ganancia ($)</option>
            </select>
        </div>
        <button wire:click="buscar" style="background-color: #7c3aed; color: white; padding: 0.5rem 1.5rem; border-radius: 0.5rem; border: none; cursor: pointer; font-weight: 600;">
            Buscar
        </button>
    </div>

    {{-- Tabla completa --}}
    <div style="background-color: white; border: 2px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden;" class="dark:bg-zinc-800 dark:border-zinc-600">
        <div style="background-color: {{ $filtroProductos == 'menos' ? '#dc2626' : '#16a34a' }}; color: white; padding: 0.75rem 1rem;">
            <h3 style="font-weight: 600;">
                @if($filtroProductos == 'mas')
                    🏆 Productos Ordenados por Más Vendidos
                @elseif($filtroProductos == 'menos')
                    📉 Productos Ordenados por Menos Vendidos
                @else
                    💰 Productos Ordenados por Mayor Ganancia
                @endif
            </h3>
        </div>
        <div style="max-height: 500px; overflow-y: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f3f4f6; position: sticky; top: 0;" class="dark:bg-zinc-700">
                    <tr>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #374151;" class="dark:text-gray-200">#</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #374151;" class="dark:text-gray-200">Código</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #374151;" class="dark:text-gray-200">Producto</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #374151;" class="dark:text-gray-200">Cantidad Vendida</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #374151;" class="dark:text-gray-200">Ingresos ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productosVendidos as $i => $p)
                    <tr style="border-bottom: 1px solid #e5e7eb; {{ $i < 3 ? 'background-color: #fef3c7;' : '' }}">
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            @if($filtroProductos != 'menos')
                                @if($i == 0) <span style="font-size: 1.25rem;">🥇</span>
                                @elseif($i == 1) <span style="font-size: 1.25rem;">🥈</span>
                                @elseif($i == 2) <span style="font-size: 1.25rem;">🥉</span>
                                @else <span style="color: #6b7280;">{{ $i + 1 }}</span>
                                @endif
                            @else
                                <span style="color: #6b7280;">{{ $i + 1 }}</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; font-family: monospace; font-size: 0.875rem; color: #6b7280;">{{ $p['codbarraspro'] }}</td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 600; color: #374151;" class="dark:text-gray-200">{{ $p['nombrepro'] }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #7c3aed;">{{ $p['total_vendido'] }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #16a34a;">${{ number_format($p['total_ingresos'], 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280;">No hay datos en este rango</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ==================== COMPARATIVO (SIN GRÁFICO, PANTALLA COMPLETA) ==================== --}}
    @if($tipoReporte == 'comparativo')
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;" class="max-md:grid-cols-1">
        {{-- Mes Anterior --}}
        <div style="background-color: #f3e8ff; padding: 2.5rem; border-radius: 1rem; text-align: center; border: 3px solid #a855f7;">
            <p style="color: #6b21a8; font-weight: 700; font-size: 1.25rem; margin-bottom: 0.5rem;">{{ now()->subMonth()->translatedFormat('F Y') }}</p>
            <p style="font-size: 3rem; font-weight: 800; color: #581c87;">${{ number_format($ventasMesAnterior, 2) }}</p>
            <p style="color: #7c3aed; font-size: 0.875rem; margin-top: 0.5rem;">Mes Anterior</p>
        </div>
        
        {{-- Mes Actual --}}
        <div style="background-color: #dbeafe; padding: 2.5rem; border-radius: 1rem; text-align: center; border: 3px solid #3b82f6;">
            <p style="color: #1e40af; font-weight: 700; font-size: 1.25rem; margin-bottom: 0.5rem;">{{ now()->translatedFormat('F Y') }}</p>
            <p style="font-size: 3rem; font-weight: 800; color: #1e3a8a;">${{ number_format($ventasMesActual, 2) }}</p>
            <p style="color: #3b82f6; font-size: 0.875rem; margin-top: 0.5rem;">Mes Actual</p>
        </div>
        
        {{-- Variación --}}
        <div style="background-color: {{ $porcentajeCambio >= 0 ? '#d1fae5' : '#fee2e2' }}; padding: 2.5rem; border-radius: 1rem; text-align: center; border: 3px solid {{ $porcentajeCambio >= 0 ? '#10b981' : '#ef4444' }};">
            <p style="color: {{ $porcentajeCambio >= 0 ? '#065f46' : '#991b1b' }}; font-weight: 700; font-size: 1.25rem; margin-bottom: 0.5rem;">Variación</p>
            <p style="font-size: 3rem; font-weight: 800; color: {{ $porcentajeCambio >= 0 ? '#064e3b' : '#7f1d1d' }};">
                {{ $porcentajeCambio >= 0 ? '↑' : '↓' }} {{ number_format(abs($porcentajeCambio), 1) }}%
            </p>
            <p style="font-size: 1.5rem; font-weight: 700; color: {{ $diferenciaDinero >= 0 ? '#16a34a' : '#dc2626' }}; margin-top: 0.5rem;">
                {{ $diferenciaDinero >= 0 ? '+' : '-' }}${{ number_format(abs($diferenciaDinero), 2) }}
            </p>
        </div>
    </div>
    @endif

    {{-- ==================== CLIENTES FRECUENTES ==================== --}}
    @if($tipoReporte == 'clientes-frecuentes')
    <div style="background-color: white; border: 2px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden;" class="dark:bg-zinc-800 dark:border-zinc-600">
        <div style="background-color: #0891b2; color: white; padding: 0.75rem 1rem;">
            <h3 style="font-weight: 600;">👥 Clientes Ordenados por Frecuencia de Compra</h3>
        </div>
        <div style="max-height: 500px; overflow-y: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f3f4f6; position: sticky; top: 0;" class="dark:bg-zinc-700">
                    <tr>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">#</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">Cliente</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">RUC/CI</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">Total Compras</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">Total Gastado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientesFrecuentes as $i => $c)
                    <tr style="border-bottom: 1px solid #e5e7eb; {{ $i < 3 ? 'background-color: #ecfeff;' : '' }}">
                        <td style="padding: 0.75rem 1rem; text-align: center; color: #6b7280;">{{ $i + 1 }}</td>
                        <td style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;" class="dark:text-gray-200">{{ $c['nombre'] }}</td>
                        <td style="padding: 0.75rem 1rem; font-family: monospace; color: #6b7280;" class="dark:text-gray-400">{{ $c['ruc_identificacion'] }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #0891b2;">{{ $c['total_compras'] }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #16a34a;">${{ number_format($c['total_gastado'], 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280;">No hay clientes registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ==================== CLIENTES MAYOR VOLUMEN ==================== --}}
    @if($tipoReporte == 'clientes-volumen')
    <div style="background-color: white; border: 2px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden;" class="dark:bg-zinc-800 dark:border-zinc-600">
        <div style="background-color: #7c3aed; color: white; padding: 0.75rem 1rem;">
            <h3 style="font-weight: 600;">💰 Clientes Ordenados por Volumen de Compra</h3>
        </div>
        <div style="max-height: 500px; overflow-y: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f3f4f6; position: sticky; top: 0;" class="dark:bg-zinc-700">
                    <tr>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">#</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">Cliente</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">RUC/CI</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">Total Compras</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; color: #374151;" class="dark:text-gray-200">Total Gastado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientesMayorVolumen as $i => $c)
                    <tr style="border-bottom: 1px solid #e5e7eb; {{ $i < 3 ? 'background-color: #f3e8ff;' : '' }}">
                        <td style="padding: 0.75rem 1rem; text-align: center; color: #6b7280;">{{ $i + 1 }}</td>
                        <td style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;" class="dark:text-gray-200">{{ $c['nombre'] }}</td>
                        <td style="padding: 0.75rem 1rem; font-family: monospace; color: #6b7280;" class="dark:text-gray-400">{{ $c['ruc_identificacion'] }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #7c3aed;">{{ $c['total_compras'] }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #16a34a;">${{ number_format($c['total_gastado'], 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280;">No hay clientes registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Chart.js --}}
    @if($tipoReporte == 'dia')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chartHorario = null;
        let chartSemana = null;

        function initCharts() {
            const container = document.getElementById('reportes-ventas-container');
            if (!container) return;

            // Datos del contenedor
            const labelsHorario = JSON.parse(container.dataset.labels || '[]');
            const valoresHorario = JSON.parse(container.dataset.valores || '[]');
            const labelsSemana = @json($chartLabelsSemana);
            const valoresSemana = @json($chartDataSemana);

            // Destruir gráficos anteriores
            if (chartHorario) {
                chartHorario.destroy();
                chartHorario = null;
            }
            if (chartSemana) {
                chartSemana.destroy();
                chartSemana = null;
            }

            // Gráfico Horario
            const canvasHorario = document.getElementById('graficoHorario');
            if (canvasHorario && labelsHorario.length > 0) {
                chartHorario = new Chart(canvasHorario, {
                    type: 'bar',
                    data: {
                        labels: labelsHorario,
                        datasets: [{
                            label: 'Clientes',
                            data: valoresHorario,
                            backgroundColor: ['#7c3aed', '#3b82f6', '#10b981', '#f59e0b'],
                            borderRadius: 8
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 }, title: { display: true, text: 'Cantidad' } },
                            x: { title: { display: true, text: 'Horario' } }
                        }
                    }
                });
            }

            // Gráfico Semana
            const canvasSemana = document.getElementById('graficoSemana');
            if (canvasSemana && labelsSemana.length > 0) {
                chartSemana = new Chart(canvasSemana, {
                    type: 'bar',
                    data: {
                        labels: labelsSemana,
                        datasets: [{
                            label: 'Ventas ($)',
                            data: valoresSemana,
                            backgroundColor: '#3b82f6',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'Monto ($)' } },
                            x: { title: { display: true, text: 'Día de la Semana' } }
                        }
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', initCharts);
        document.addEventListener('livewire:initialized', initCharts);

        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => setTimeout(initCharts, 50));
            Livewire.hook('commit', ({ succeed }) => {
                succeed(() => setTimeout(initCharts, 100));
            });
        }
    </script>
    @endif
</div>