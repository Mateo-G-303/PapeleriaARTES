<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">Proveedores</h2>

                <button wire:click="abrirModal"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    + Nuevo Proveedor
                </button>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-3 py-2">RUC</th>
                        <th class="px-3 py-2">Nombre</th>
                        <th class="px-3 py-2">Correo</th>
                        <th class="px-3 py-2">Teléfono</th>
                        <th class="px-3 py-2 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($proveedores as $prov)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $prov->rucprov }}</td>
                            <td class="px-3 py-2 font-semibold">{{ $prov->nombreprov }}</td>
                            <td class="px-3 py-2">{{ $prov->correoprov }}</td>
                            <td class="px-3 py-2">{{ $prov->telefonoprov }}</td>
                            <td class="px-3 py-2 flex gap-2 justify-center">
                                <button
                                    wire:click="editar({{ $prov->idprov }})"
                                    class="bg-yellow-400 px-2 py-1 rounded">
                                    Editar
                                </button>

                                <button
                                    wire:click="borrar({{ $prov->idprov }})"
                                    class="bg-red-600 text-white px-2 py-1 rounded">
                                    Borrar
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    @if($proveedores->count() === 0)
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                No hay proveedores registrados
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    @if($modal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded w-96">
                <h3 class="text-lg font-bold mb-4">
                    {{ $idprov ? 'Editar Proveedor' : 'Nuevo Proveedor' }}
                </h3>

                <input wire:model.defer="rucprov"
                    class="w-full mb-2 border rounded px-2 py-1"
                    placeholder="RUC">

                <input wire:model.defer="nombreprov"
                    class="w-full mb-2 border rounded px-2 py-1"
                    placeholder="Nombre">

                <input wire:model.defer="correoprov"
                    class="w-full mb-2 border rounded px-2 py-1"
                    placeholder="Correo">

                <input wire:model.defer="telefonoprov"
                    class="w-full mb-4 border rounded px-2 py-1"
                    placeholder="Teléfono">

                <div class="flex justify-end gap-2">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 border rounded">
                        Cancelar
                    </button>

                    <button wire:click="guardar"
                        class="bg-blue-600 text-white px-4 py-2 rounded">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>