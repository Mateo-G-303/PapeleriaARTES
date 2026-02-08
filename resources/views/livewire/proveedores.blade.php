<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if($modal)
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 transition-all duration-300">

            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-white">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $idprov ? 'Editar Proveedor' : 'Nuevo Proveedor' }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Registra la información del proveedor.</p>
                </div>
                <button wire:click="cerrarModal" class="text-gray-400 hover:text-red-500 transition-colors duration-200">
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
                        <flux:input label="RUC" wire:model.blur="rucprov" placeholder="Ej: 0999999999001" maxlength="13" inputmode="numeric" pattern="[0-9]*" />
                        @error('rucprov')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <flux:input label="Nombre del Proveedor" wire:model="nombreprov" placeholder="Ej: Proveedor XYZ" />
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <flux:input label="Correo" wire:model="correoprov" placeholder="correo@proveedor.com" />
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <flux:input label="Teléfono" wire:model="telefonoprov" placeholder="0987654321" />
                    </div>

                    <div class="col-span-12">
                        <flux:input label="Dirección" wire:model="direccionprov" placeholder="Dirección del proveedor" />
                    </div>

                </div>

                <div class="flex items-center justify-end gap-4 pt-8 mt-6 border-t border-gray-100">
                    <button wire:click="cerrarModal" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-medium bg-white hover:bg-gray-50 shadow-sm transition-all">
                        Cancelar
                    </button>

                    <button
                        wire:click="guardar"
                        style="background-color:#4f46e5; color:white;"
                        class="px-8 py-3 rounded-lg font-bold shadow-lg transition flex items-center gap-2 hover:opacity-90">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>

                        <span>
                            {{ $idprov ? 'Actualizar Proveedor' : 'Guardar Proveedor' }}
                        </span>
                    </button>
                </div>
            </div>
        </div>

        @else
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div class="flex justify-between items-center mb-6">



                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/proveedores.svg') }}"
                        alt="Proveedores"
                        class="w-14 h-14">
                

                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Proveedores</h2>
                    <p class="text-sm text-gray-500">Gestión de proveedores.</p>
                </div>
                </div>
                {{-- DERECHA: BUSCADOR + BOTÓN --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

        <input
            type="text"
            wire:model.live="search"
            placeholder="Buscar por RUC, nombre, teléfono o dirección"
            class="w-full sm:w-72 px-4 py-2 border rounded-lg
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">

        @if(Auth::user()->tienePermiso('proveedores.crear'))
        <flux:button wire:click="abrirModal" variant="primary" icon="plus">
            Nuevo Proveedor
        </flux:button>
        @endif
    </div>
                
            </div>
            
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RUC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Dirección</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($proveedores as $prov)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-6 py-4 text-sm">{{ $prov->rucprov }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $prov->nombreprov }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $prov->correoprov }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $prov->telefonoprov }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $prov->direccionprov}}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @if(Auth::user()->tienePermiso('proveedores.editar'))
                                    <flux:button size="sm" wire:click="editar({{ $prov->idprov }})">Editar</flux:button>
                                    @endif
                                    @if(Auth::user()->tienePermiso('proveedores.eliminar'))
                                    <flux:button size="sm" variant="danger" wire:click="borrar({{ $prov->idprov }})" wire:confirm="¿Seguro que deseas eliminar este proveedor?">Borrar</flux:button>
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
            {{ $proveedores->links() }}
        </div>
        
        @endif
    </div>
</div>