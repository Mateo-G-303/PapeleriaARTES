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
        @if(Auth::user()->tienePermiso('configuraciones.ver'))
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
        @endif


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

        {{-- Solo el Administrador y el Auditor pueden ver el acceso a Seguridad --}}
        @if(in_array(Auth::user()->rol->nombrerol, ['Administrador', 'Auditor']))
        <a href="{{ route('admin.reportes.seguridad') }}" class="block cursor-pointer bg-white rounded-xl shadow p-6 hover:shadow-lg transition group">
            <div class="bg-white p-4 rounded-xl shadow-[10px_12px_25px_rgba(0,0,0,0.28)] flex items-center gap-4 group-hover:scale-105 transition-transform">
                <div class="w-15 h-15 flex items-center justify-center text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-lg text-gray-800">Logs y Auditoría</h2>
                    <p class="text-sm text-gray-500">Seguridad del Sistema</p>
                </div>
            </div>
        </a>
        @endif

        {{-- REPORTES FUTUROS (DESHABILITADOS) --}}
        @for($i = 0; $i < 2; $i++)
            <div class="bg-gray-100 rounded-xl p-6 text-gray-400 cursor-not-allowed">
            <h2 class="font-bold">Próximamente</h2>
            <p class="text-sm">Nuevo reporte</p>
    </div>
    @endfor

</div>
</div>