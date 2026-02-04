<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-100">
            
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Logs de Seguridad y Anomalías</h2>
                    <p class="text-sm text-gray-500 mt-1">Monitoreo de eventos críticos e intentos de acceso en tiempo real.</p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Fecha y Hora
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Nivel
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Usuario
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Detalle del Evento
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($log->fechalogs)->format('d/m/Y H:i:s') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        // Asignamos colores según el ID del nivel
                                        $claseColor = match($log->idnivel) {
                                            1 => 'bg-red-100 text-red-800 border-red-200',    // Crítico
                                            2 => 'bg-yellow-100 text-yellow-800 border-yellow-200', // Advertencia
                                            default => 'bg-blue-100 text-blue-800 border-blue-200', // Info
                                        };
                                        
                                        // Nombre del nivel (seguro por si es nulo)
                                        $nombreNivel = $log->nivel->nombrenivel ?? 'N/A';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $claseColor }}">
                                        {{ $nombreNivel }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div class="flex items-center">
                                        @if($log->user)
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-2 uppercase">
                                                {{ substr($log->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <span>{{ $log->user->name }}</span>
                                        @else
                                            <span class="text-gray-400 italic font-light flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                Sistema / Desconocido
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ $log->mensajelogs }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p>No se han registrado anomalías recientes.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>

        </div>
    </div>
</div>