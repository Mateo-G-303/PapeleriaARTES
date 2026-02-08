<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if($modal)
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 transition-all duration-300">

            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-white">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $idcat_editar ? 'Editar Categoría' : 'Nueva Categoría' }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Define el nombre y descripción para agrupar tus productos.</p>
                </div>
                <button wire:click="limpiarCampos" class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-8">

                <div class="grid grid-cols-12 gap-y-8 gap-x-6">

                    <div class="col-span-12">
                        <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-4 border-b border-indigo-100 pb-2">
                            Información General
                        </h3>
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <flux:input label="Nombre de la Categoría" wire:model="nombrecat" placeholder="Ej: Papelería, Oficina, Arte..." class="w-full" />
                    </div>

                    <div class="hidden md:block col-span-6"></div>

                    <div class="col-span-12">
                        <flux:input label="Descripción" wire:model="descripcioncat" placeholder="Breve detalle sobre qué tipo de productos incluye esta categoría." />
                    </div>

                </div>

                <div class="flex items-center justify-end gap-4 pt-8 mt-6 border-t border-gray-100">

                    <button wire:click="limpiarCampos" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-medium bg-white hover:bg-gray-50 shadow-sm transition-all">
                        Cancelar
                    </button>

                    <button
                        wire:click="guardar"
                        style="background-color:#4f46e5; color:white;"
                        class="px-8 py-3 rounded-lg font-bold shadow-lg transition flex items-center gap-2 hover:opacity-90">

                        <svg class="w-5 h-5" style="color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>

                        <span>
                            {{ $idcat_editar ? 'Actualizar Categoría' : 'Guardar Categoría' }}
                        </span>
                    </button>

                </div>

            </div>
        </div>

        @else
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            {{-- IZQUIERDA: TÍTULO --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Categorías</h2>
                <p class="text-sm text-gray-500">Organización de tu inventario.</p>
            </div>

            {{-- DERECHA: BUSCADOR + BOTÓN --}}
                <div class="flex items-center gap-3">
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Buscar por nombre de categoría"
                        class="w-64 px-4 py-2 border rounded-lg">

                    @if(Auth::user()->tienePermiso('categorias.crear'))
                        <flux:button wire:click="crear" variant="primary" icon="plus">
                            Nueva Categoría
                        </flux:button>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categorias as $cat)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $cat->idcat }}</td>

                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-900">{{ $cat->nombrecat }}</span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $cat->descripcioncat ?? 'Sin descripción' }}
                            </td>

                            <td class="px-6 py-4 text-sm font-medium text-right">
                                <div class="flex justify-end gap-2">
                                    @if(Auth::user()->tienePermiso('categorias.editar'))
                                    <flux:button size="sm" wire:click="editar({{ $cat->idcat }})">Editar</flux:button>
                                    @endif
                                    @if(Auth::user()->tienePermiso('categorias.eliminar'))
                                    <flux:button size="sm" variant="danger" wire:click="borrar({{ $cat->idcat }})" wire:confirm="¿Seguro que deseas eliminar esta categoría?">Borrar</flux:button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $categorias->links() }}
        </div>
        @endif
    </div>
    
</div>