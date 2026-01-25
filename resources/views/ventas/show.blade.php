<x-layouts.app.sidebar title="Detalle de Venta">
    <flux:main>
        <div class="container mx-auto px-4 py-8">
            <div style="max-width: 72rem; margin: 0 auto;">
                <!-- Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Detalle de Venta #{{ $venta->idven }}</h1>
                    <div style="display: flex; gap: 0.75rem;">
                        <a href="{{ route('ventas.pdf', $venta->idven) }}" 
                           style="background-color: #dc2626; color: white; font-weight: 700; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;"
                           onmouseover="this.style.backgroundColor='#b91c1c'"
                           onmouseout="this.style.backgroundColor='#dc2626'">
                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.25rem; width: 1.25rem;" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Descargar PDF
                        </a>
                        <a href="{{ route('ventas.index') }}" 
                           style="background-color: #16a34a; color: white; font-weight: 700; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;"
                           onmouseover="this.style.backgroundColor='#15803d'"
                           onmouseout="this.style.backgroundColor='#16a34a'">
                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.25rem; width: 1.25rem;" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Volver
                        </a>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                    <!-- Información de la venta -->
                    <div style="background-color: #f9fafb; border: 2px solid #d1d5db; border-radius: 0.75rem; padding: 1.5rem;" class="dark:bg-zinc-800 dark:border-zinc-600">
                        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937; display: flex; align-items: center; gap: 0.5rem;" class="dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5rem; width: 1.5rem; color: #7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Información de la Venta
                        </h2>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="border-bottom: 2px solid #e5e7eb; padding-bottom: 0.75rem;">
                                <p style="font-size: 0.875rem; font-weight: 600; color: #6b7280;">Fecha:</p>
                                <p style="font-weight: 700; color: #1f2937; font-size: 1.125rem;" class="dark:text-white">{{ $venta->fechaven->format('d/m/Y H:i') }}</p>
                            </div>
                            <div style="border-bottom: 2px solid #e5e7eb; padding-bottom: 0.75rem;">
                                <p style="font-size: 0.875rem; font-weight: 600; color: #6b7280;">Vendedor:</p>
                                <p style="font-weight: 700; color: #1f2937; font-size: 1.125rem;" class="dark:text-white">{{ $venta->usuario->name ?? 'N/A' }}</p>
                            </div>
                            <div style="border-bottom: 2px solid #e5e7eb; padding-bottom: 0.75rem;">
                                <p style="font-size: 0.875rem; font-weight: 600; color: #6b7280;">Cliente:</p>
                                <p style="font-weight: 700; color: #1f2937; font-size: 1.125rem;" class="dark:text-white">{{ $venta->cliente->nombre ?? 'Consumidor Final' }}</p>
                            </div>
                            <div style="padding-top: 0.5rem;">
                                <p style="font-size: 0.875rem; font-weight: 600; color: #6b7280;">Total:</p>
                                <p style="font-size: 1.875rem; font-weight: 700; color: #16a34a;">${{ number_format($venta->totalven, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Productos vendidos -->
                    <div style="background-color: #f9fafb; border: 2px solid #d1d5db; border-radius: 0.75rem; overflow: hidden;" class="dark:bg-zinc-800 dark:border-zinc-600">
                        <div style="background-color: #7c3aed; color: white; padding: 1rem;">
                            <h2 style="font-size: 1.25rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Productos Vendidos
                            </h2>
                        </div>
                        <div style="overflow-x: auto;">
                            <table style="min-width: 100%; width: 100%;">
                                <thead style="background-color: #e5e7eb;" class="dark:bg-zinc-900">
                                    <tr>
                                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">Código</th>
                                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">Producto</th>
                                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">P. Unit.</th>
                                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">Cant.</th>
                                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody style="background-color: white;" class="dark:bg-zinc-800">
                                    @foreach($venta->detalles as $detalle)
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 0.75rem 1rem;">
                                            <span style="background-color: #e5e7eb; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-family: monospace; font-weight: 700; color: #374151;" class="dark:bg-zinc-700 dark:text-gray-200">{{ $detalle->producto->codbarraspro ?? 'N/A' }}</span>
                                        </td>
                                        <td style="padding: 0.75rem 1rem; font-weight: 700; color: #374151;" class="dark:text-gray-200">{{ $detalle->producto->nombrepro ?? 'N/A' }}</td>
                                        <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #374151;" class="dark:text-gray-200">${{ number_format($detalle->preciounitariodven, 2) }}</td>
                                        <td style="padding: 0.75rem 1rem; text-align: center;">
                                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border-radius: 50%; background-color: #7c3aed; color: white; font-weight: 700; font-size: 0.875rem;">
                                                {{ $detalle->cantidaddven }}
                                            </span>
                                        </td>
                                        <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #16a34a;">${{ number_format($detalle->subtotaldven, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="background-color: #e5e7eb;" class="dark:bg-zinc-900">
                                    <tr>
                                        <td colspan="4" style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #1f2937; text-transform: uppercase;" class="dark:text-white">TOTAL:</td>
                                        <td style="padding: 0.75rem 1rem; text-align: right; font-size: 1.25rem; font-weight: 700; color: #16a34a;">${{ number_format($venta->totalven, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </flux:main>
</x-layouts.app.sidebar>