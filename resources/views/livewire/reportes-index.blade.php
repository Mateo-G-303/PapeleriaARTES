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
        <a
            href="{{ route('reportes.compras') }}"
            class="block cursor-pointer bg-white rounded-xl shadow p-6 hover:shadow-lg transition">

            <div class="bg-white p-4 rounded-xl 
            shadow-[10px_12px_25px_rgba(0,0,0,0.28)]
            flex items-center gap-4">
                {{-- Imagen --}}
                <img
                    src="{{ asset('img/Rcompras.svg') }}"
                    alt="Compras"
                    class="w-15 h-15 object-contain">
                <div>
                    <h2 class="font-bold text-lg">Reporte de Compras</h2>
                    <p class="text-sm text-gray-500">
                        Gastos por proveedor
                    </p>
                </div>
            </div>

        </a>

        {{-- REPORTES FUTUROS (DESHABILITADOS) --}}
        @for($i = 0; $i < 4; $i++)
            <div
            class="bg-gray-100 rounded-xl p-6 text-gray-400 cursor-not-allowed">

            <h2 class="font-bold">Próximamente</h2>
            <p class="text-sm">Nuevo reporte</p>
            </div>
        @endfor
    <a
        href="{{ route('reportes.productosCategoria') }}"
        class="block cursor-pointer bg-white rounded-xl shadow p-6 hover:shadow-lg transition">

        <div class="bg-white p-4 rounded-xl 
            shadow-[10px_12px_25px_rgba(0,0,0,0.28)]
            flex items-center gap-4">
            {{-- Imagen --}}
                <img
                    src="{{ asset('img/Graficobarras.png') }}"
                    alt="Compras"
                    class="w-15 h-15 object-contain">
            <div>
                <h2 class="font-bold text-lg">Gráfico por Categorias</h2>
                <p class="text-sm text-gray-500">
                    Productos Por Categoría
                </p>
            </div>
        </div>

    </a>

</div>
</div>