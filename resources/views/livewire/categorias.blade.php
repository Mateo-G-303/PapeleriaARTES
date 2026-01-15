<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Categorías de Productos</h2>
                <flux:button wire:click="crear" variant="primary" icon="plus">Nueva Categoría</flux:button>
            </div>

            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categorias as $cat)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $cat->idcat }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $cat->nombrecat }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $cat->descripcioncat }}</td>
                                <td class="px-6 py-4 text-sm font-medium flex gap-2">
                                    <flux:button size="sm" wire:click="editar({{ $cat->idcat }})">Editar</flux:button>
                                    <flux:button size="sm" variant="danger" wire:click="borrar({{ $cat->idcat }})" wire:confirm="¿Seguro?">Borrar</flux:button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($modal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                    <h3 class="text-lg font-bold mb-4">{{ $idcat_editar ? 'Editar' : 'Nueva' }} Categoría</h3>
                    <div class="space-y-4">
                        <flux:input label="Nombre" wire:model="nombrecat" />
                        <flux:input label="Descripción" wire:model="descripcioncat" />
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <flux:button wire:click="limpiarCampos">Cancelar</flux:button>
                        <flux:button variant="primary" wire:click="guardar">Guardar</flux:button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>