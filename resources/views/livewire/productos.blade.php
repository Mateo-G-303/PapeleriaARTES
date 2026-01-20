<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if($modal)
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 transition-all duration-300">

            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-white">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $id_producto_editar ? 'Editar Producto' : 'Nuevo Producto' }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Complete la información para registrar el ítem en inventario.</p>
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
                            1. Información Básica
                        </h3>
                    </div>

                    <div class="col-span-12 md:col-span-4">
                        <flux:input label="Código de Barras" wire:model="codbarraspro" placeholder="Ej: 78610..." class="w-full" />
                    </div>

                    <div class="col-span-12 md:col-span-8">
                        <flux:input label="Nombre del Producto" wire:model="nombrepro" placeholder="Nombre comercial del artículo" class="w-full" />
                    </div>

                    <div class="col-span-12">
                        <flux:label class="mb-1 block font-medium text-gray-700">Categoría</flux:label>
                        <div class="relative">
                            <select wire:model="idcat" class="block w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm shadow-sm transition">
                                <option value="">Seleccione una categoría...</option>
                                @foreach($categorias as $cat)
                                <option value="{{ $cat->idcat }}">{{ $cat->nombrecat }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('idcat') <span class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-12 mt-4">
                        <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-4 border-b border-indigo-100 pb-2">
                            2. Costos y Precios
                        </h3>
                    </div>

                    <div class="col-span-12 md:col-span-6 bg-blue-50/30 p-4 rounded-lg border border-blue-100">
                        <flux:input label="Costo de Compra ($)" wire:model="preciocomprapro" type="number" step="0.01" />
                        <p class="text-xs text-blue-400 mt-1 italic">Costo neto proveedor</p>
                    </div>

                    <div class="col-span-12 md:col-span-6 bg-green-50/30 p-4 rounded-lg border border-green-100">
                        <flux:input label="Precio Venta Público ($)" wire:model="precioventapro" type="number" step="0.01" />
                        <p class="text-xs text-green-500 mt-1 italic font-bold">Precio final cliente</p>
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <flux:input label="Precio Mínimo (Oferta)" wire:model="preciominpro" type="number" step="0.01" />
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <flux:input label="Precio Máximo (Referencia)" wire:model="preciomaxpro" type="number" step="0.01" />
                    </div>


                    <div class="col-span-12 mt-4">
                        <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-4 border-b border-indigo-100 pb-2">
                            3. Gestión de Inventario
                        </h3>
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <flux:input label="Stock Físico Actual" wire:model="stockpro" type="number" />
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <flux:input label="Alerta de Stock Mínimo" wire:model="stockminpro" type="number" description="El sistema avisará cuando llegue a esta cantidad." />
                    </div>

                </div>

                <div class="flex items-center justify-end gap-4 pt-8 mt-6 border-t border-gray-100">
                    <button wire:click="limpiarCampos" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-medium bg-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-all shadow-sm">
                        Cancelar
                    </button>

                    <button
                        wire:click="guardar"
                        style="background-color:#2563eb; color:white;"
                        class="px-8 py-3 rounded-lg font-bold shadow-lg transition flex items-center gap-2 hover:opacity-90">

                        <svg class="w-5 h-5" style="color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>

                        <span>
                            {{ $id_producto_editar ? 'Actualizar' : 'Guardar' }}
                        </span>
                    </button>


                </div>

            </div>
        </div>

        @else
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Inventario</h2>
                    <p class="text-sm text-gray-500">Gestión de productos y existencias.</p>
                </div>
                <flux:button wire:click="exportarCSV" class="bg-green-600 hover:bg-green-700 text-white border-none" icon="arrow-down-tray">
                    <span wire:loading.remove wire:target="exportarCSV">Exportar Excel</span>
                    <span wire:loading wire:target="exportarCSV">Generando...</span>
                </flux:button>
                <flux:button wire:click="crear" variant="primary" icon="plus">
                    Nuevo Producto
                </flux:button>

            </div>

            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cat.</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Costo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">P. Venta</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rango</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($productos as $producto)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-sm text-gray-900 font-mono">{{ $producto->codbarraspro }}</td>

                            <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                {{ $producto->nombrepro }}
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-500">
                                {{ $producto->categoria->nombrecat ?? 'Sin Cat' }}
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-500">
                                ${{ number_format($producto->preciocomprapro, 2) }}
                            </td>

                            <td class="px-4 py-4 text-sm font-bold text-gray-900">
                                ${{ number_format($producto->precioventapro, 2) }}
                            </td>

                            <td class="px-4 py-4 text-xs text-gray-500">
                                ${{ number_format($producto->preciominpro, 0) }} - ${{ number_format($producto->preciomaxpro, 0) }}
                            </td>

                            <td class="px-4 py-4 text-center">
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $producto->stockpro <= $producto->stockminpro ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $producto->stockpro }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-sm font-medium flex gap-2">
                                <flux:button size="sm" wire:click="editar({{ $producto->idpro }})">Editar</flux:button>
                                <flux:button size="sm" variant="danger" wire:click="borrar({{ $producto->idpro }})" wire:confirm="¿Seguro que deseas eliminar?">Borrar</flux:button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-500">No hay productos registrados aún.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>