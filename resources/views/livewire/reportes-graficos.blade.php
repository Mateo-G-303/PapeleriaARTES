<div class="py-12 bg-gray-50 min-h-screen"
    id="reportes-container"
    {{-- Pasamos todos los datos como atributos JSON --}}
    data-logs-labels='@json($nivelesLabels)'
    data-logs-count='@json($nivelesCount)'
    data-users-labels='@json($usuariosLabels)'
    data-users-count='@json($usuariosCount)'
    data-trend-labels='@json($trendLabels)'
    data-trend-count='@json($trendCount)'
    data-actions-labels='@json($accionesLabels)'
    data-actions-count='@json($accionesCount)'>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Monitor de Seguridad</h2>
            <p class="mt-2 text-sm text-gray-600">Análisis de actividad y riesgos en Papelería ARTES.</p>
        </div>

        <div class="mb-8">
            <a href="{{ route('reportes.index') }}"
                class="px-3 py-1.5 border rounded-lg text-sm font-semibold
                  text-gray-600 hover:bg-red-50 hover:text-red-600 transition">
                Salir
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Eventos Hoy</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalHoy }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full text-blue-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Alertas Críticas</p>
                        <p class="text-3xl font-bold text-red-600">{{ $totalCriticos }}</p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-full text-red-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Usuarios Totales</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalUsuarios }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full text-green-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

            <div class="bg-white p-6 rounded-xl shadow border border-gray-100 flex flex-col">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Niveles de Gravedad</h3>
                <div class="relative flex-grow" style="min-height: 250px;">
                    <canvas id="graficoLogs"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow border border-gray-100 flex flex-col">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Usuarios Más Activos</h3>
                <div class="relative flex-grow" style="min-height: 250px;">
                    <canvas id="graficoUsuarios"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow border border-gray-100 flex flex-col">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tendencia de Anomalías (7 días)</h3>
                <div class="relative flex-grow" style="min-height: 250px;">
                    <canvas id="graficoTendencia"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow border border-gray-100 flex flex-col">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Acciones Frecuentes</h3>
                <div class="relative flex-grow" style="min-height: 250px;">
                    <canvas id="graficoAcciones"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Recuperar datos desde el HTML
            const container = document.getElementById('reportes-container');

            const logsLabels = JSON.parse(container.dataset.logsLabels);
            const logsCount = JSON.parse(container.dataset.logsCount);
            const usersLabels = JSON.parse(container.dataset.usersLabels);
            const usersCount = JSON.parse(container.dataset.usersCount);

            // Nuevos datos
            const trendLabels = JSON.parse(container.dataset.trendLabels);
            const trendCount = JSON.parse(container.dataset.trendCount);
            const actionsLabels = JSON.parse(container.dataset.actionsLabels);
            const actionsCount = JSON.parse(container.dataset.actionsCount);

            // 1. Gráfico Logs (Pastel)
            new Chart(document.getElementById('graficoLogs'), {
                type: 'doughnut',
                data: {
                    labels: logsLabels,
                    datasets: [{
                        data: logsCount,
                        backgroundColor: ['#ef4444', '#eab308', '#3b82f6'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    maintainAspectRatio: false
                }
            });

            // 2. Gráfico Usuarios (Barras Verticales)
            new Chart(document.getElementById('graficoUsuarios'), {
                type: 'bar',
                data: {
                    labels: usersLabels,
                    datasets: [{
                        label: 'Acciones',
                        data: usersCount,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // 3. Gráfico Tendencia (Línea Suave)
            new Chart(document.getElementById('graficoTendencia'), {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Incidentes',
                        data: trendCount,
                        fill: true,
                        borderColor: 'rgb(99, 102, 241)', // Color Índigo
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4 // Curva suave
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // 4. Gráfico Acciones (Barras Horizontales)
            new Chart(document.getElementById('graficoAcciones'), {
                type: 'bar',
                data: {
                    labels: actionsLabels,
                    datasets: [{
                        label: 'Frecuencia',
                        data: actionsCount,
                        backgroundColor: 'rgba(255, 159, 64, 0.6)', // Naranja
                        borderColor: 'rgb(255, 159, 64)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // ESTO HACE LAS BARRAS HORIZONTALES
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</div>