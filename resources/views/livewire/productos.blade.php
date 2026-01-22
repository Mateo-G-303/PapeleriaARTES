<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Inventario</h2>
                @if(Auth::user()->tienePermiso('productos.crear'))
                <flux:button wire:click="crear" variant="primary" icon="plus">
                    Nuevo Producto
                </flux:button>
                @endif
            </div>

            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($productos as $producto)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $producto->codbarraspro }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $producto->nombrepro }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $producto->categoria->nombrecat ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($producto->precioventapro, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $producto->stockpro > 5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $producto->stockpro }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium flex gap-2">
                                @if(Auth::user()->tienePermiso('productos.editar'))
                                <flux:button size="sm" wire:click="editar({{ $producto->idpro }})">Editar</flux:button>
                                @endif
                                @if(Auth::user()->tienePermiso('productos.eliminar'))
                                <flux:button size="sm" variant="danger" wire:click="borrar({{ $producto->idpro }})" wire:confirm="¿Seguro que deseas eliminar?">Borrar</flux:button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">No hay productos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($modal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6 relative">

                    <h3 class="text-lg font-bold mb-4 text-gray-900">
                        {{ $id_producto_editar ? 'Editar Producto' : 'Crear Nuevo Producto' }}
                    </h3>

                    <div class="space-y-4">

                        <flux:input label="Código de Barras" wire:model="codbarraspro" type="text" placeholder="Ej: 78610001" />

                        <flux:input label="Nombre del Producto" wire:model="nombrepro" type="text" placeholder="Nombre del artículo" />

                        <div>
                            <flux:label>Categoría</flux:label>
                            <select wire:model="idcat" class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                                <option value="">Seleccione una categoría...</option>
                                @foreach($categorias as $cat)
                                <option value="{{ $cat->idcat }}">{{ $cat->nombrecat }}</option>
                                @endforeach
                            </select>
                            @error('idcat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <flux:input label="Precio Venta" wire:model="precioventapro" type="number" step="0.01" icon="currency-dollar" />
                            <flux:input label="Stock" wire:model="stockpro" type="number" />
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <flux:button wire:click="limpiarCampos">Cancelar</flux:button>
                        <flux:button variant="primary" wire:click="guardar">
                            {{ $id_producto_editar ? 'Actualizar' : 'Guardar' }}
                        </flux:button>
                    </div>

                </div>
            </div>
            @endif

        </div>
    </div>
</div>