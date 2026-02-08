<div class="bg-white rounded-xl shadow p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold">Costos, Ventas y Ganancias por Mes</h3>

        <a href="{{ route('reportes.index') }}"
           class="px-3 py-1.5 border rounded-lg text-sm font-semibold
                  text-gray-600 hover:bg-red-50 hover:text-red-600 transition">
            Salir
        </a>
    </div>

    <div wire:ignore class="h-[320px]">
        <canvas id="costosVentasGananciasChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const meses = @json($meses);
            const costos = @json($costos);
            const ventas = @json($ventas);
            const ganancias = @json($ganancias);

            if (!meses.length) return;

            const nombresMeses = [
                'Enero','Febrero','Marzo','Abril','Mayo','Junio',
                'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
            ];

            const labels = meses.map(m => nombresMeses[m - 1]);

            const ctx = document
                .getElementById('costosVentasGananciasChart')
                .getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Costos',
                            data: costos,
                            backgroundColor: '#ef4444',
                            borderRadius: 6
                        },
                        {
                            label: 'Ventas',
                            data: ventas,
                            backgroundColor: '#3b82f6',
                            borderRadius: 6
                        },
                        {
                            label: 'Ganancias',
                            data: ganancias,
                            backgroundColor: '#22c55e',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

</div>