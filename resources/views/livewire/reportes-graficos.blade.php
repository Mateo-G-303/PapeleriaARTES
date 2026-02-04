<div class="py-12 bg-gray-50 min-h-screen"
    id="reportes-container"
    data-logs-labels='@json($nivelesLabels)'
    data-logs-count='@json($nivelesCount)'
    data-auditoria-labels='@json($usuariosLabels)'
    data-auditoria-count='@json($accionesCount)'>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tablero de Control y Estadísticas</h2>
            <p class="mt-2 text-sm text-gray-600">Visualización de seguridad y actividad del sistema en tiempo real.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Distribución de Anomalías</h3>
                    <p class="text-xs text-gray-500">Eventos críticos vs informativos registrados recientemente.</p>
                </div>
                <div class="relative flex-grow" style="min-height: 300px;">
                    <canvas id="graficoLogs"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Top Usuarios con mayor Actividad</h3>
                    <p class="text-xs text-gray-500">Cantidad de acciones de auditoría por cada usuario.</p>
                </div>
                <div class="relative flex-grow" style="min-height: 300px;">
                    <canvas id="graficoAuditoria"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // 1. Extraer los datos de los atributos data- del contenedor principal
            const container = document.getElementById('reportes-container');

            const logsLabels = JSON.parse(container.dataset.logsLabels);
            const logsCount = JSON.parse(container.dataset.logsCount);
            const auditoriaLabels = JSON.parse(container.dataset.auditoriaLabels);
            const auditoriaCount = JSON.parse(container.dataset.auditoriaCount);

            // --- CONFIGURACIÓN GRÁFICO DE LOGS (DOUGHNUT) ---
            const ctxLogs = document.getElementById('graficoLogs').getContext('2d');
            new Chart(ctxLogs, {
                type: 'doughnut',
                data: {
                    labels: logsLabels,
                    datasets: [{
                        data: logsCount,
                        backgroundColor: [
                            '#ef4444', // Rojo - Crítico
                            '#eab308', // Amarillo - Advertencia
                            '#3b82f6' // Azul - Informativo
                        ],
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // --- CONFIGURACIÓN GRÁFICO DE AUDITORÍA (BAR) ---
            const ctxAuditoria = document.getElementById('graficoAuditoria').getContext('2d');
            new Chart(ctxAuditoria, {
                type: 'bar',
                data: {
                    labels: auditoriaLabels,
                    datasets: [{
                        label: 'Acciones Realizadas',
                        data: auditoriaCount,
                        backgroundColor: 'rgba(79, 70, 229, 0.6)', // Color Índigo
                        borderColor: 'rgb(79, 70, 229)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
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
        });
    </script>
</div>