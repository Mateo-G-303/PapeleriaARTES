<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Reportes
            </h1>
            <p class="text-gray-500 mt-1">
                Visualiza y analiza la información del sistema
            </p>
        </div>
        <img
            src="{{ asset('img/reportes.svg') }}"
            alt="Reportes"
            class="h-30 hidden md:block">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- REPORTE COMPRAS --}}
        <a href="{{ route('reportes.compras') }}" class="block cursor-pointer bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="bg-white p-4 rounded-xl shadow-[10px_12px_25px_rgba(0,0,0,0.28)] flex items-center gap-4">
                <img src="{{ asset('img/Rcompras.svg') }}" alt="Compras" class="w-15 h-15 object-contain">
                <div>
                    <h2 class="font-bold text-lg">Reporte de Compras</h2>
                    <p class="text-sm text-gray-500">Gastos por proveedor</p>
                </div>
            </div>
        </a>

        {{-- EXPORTAR DATOS EXCEL --}}
        <a href="{{ route('admin.configuraciones.exportar-datos') }}" class="block cursor-pointer bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="bg-white p-4 rounded-xl shadow-[10px_12px_25px_rgba(0,0,0,0.28)] flex items-center gap-4">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div>
                    <h2 class="font-bold text-lg">Exportar Datos</h2>
                    <p class="text-sm text-gray-500">Excel completo del negocio</p>
                </div>
            </div>
        </a>

        {{-- GRÁFICO POR CATEGORÍAS --}}
        <a href="{{ route('reportes.productosCategoria') }}" class="block cursor-pointer bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="bg-white p-4 rounded-xl shadow-[10px_12px_25px_rgba(0,0,0,0.28)] flex items-center gap-4">
                <img src="{{ asset('img/Graficobarras.png') }}" alt="Categorias" class="w-15 h-15 object-contain">
                <div>
                    <h2 class="font-bold text-lg">Gráfico por Categorias</h2>
                    <p class="text-sm text-gray-500">Productos Por Categoría</p>
                </div>
            </div>
        </a>

        {{-- REPORTES FUTUROS (DESHABILITADOS) --}}
        @for($i = 0; $i < 3; $i++)
            <div class="bg-gray-100 rounded-xl p-6 text-gray-400 cursor-not-allowed">
                <h2 class="font-bold">Próximamente</h2>
                <p class="text-sm">Nuevo reporte</p>
            </div>
        @endfor

    </div>
</div>