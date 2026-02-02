<div class="bg-white rounded-xl shadow p-6">
    
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold">Productos por categoría</h3>

        <a href="{{ route('reportes.index') }}"
           class="px-3 py-1.5 border rounded-lg text-sm font-semibold
                  text-gray-600 hover:bg-red-50 hover:text-red-600 transition">
            Salir
        </a>
    </div>

    <div wire:ignore class="h-[320px]">
        <canvas id="productosPorCategoriaChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const categorias = @json($categorias);
            const totales = @json($totales);

            if (!categorias.length) return;

            const colores = [
                '#3b82f6', // azul
                '#22c55e', // verde
                '#f97316', // naranja
                '#a855f7', // morado
                '#ef4444', // rojo
                '#14b8a6', // teal
                '#eab308'  // amarillo
            ];

            const ctx = document
                .getElementById('productosPorCategoriaChart')
                .getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: categorias,
                    datasets: [{
                        label: 'Productos',
                        data: totales,
                        backgroundColor: totales.map(
                            (_, i) => colores[i % colores.length]
                        ),
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
    
</div>
