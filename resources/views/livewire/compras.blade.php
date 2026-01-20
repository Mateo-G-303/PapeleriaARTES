<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            {{-- ENCABEZADO --}}
            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">Compras</h2>

                <button
                    wire:click="abrirModal"
                    class="bg-blue-600 text-white px-4 py-2 rounded"
                >
                    + Nueva Compra
                </button>
            </div>

            {{-- TABLA DE COMPRAS --}}
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-3 py-2">Código</th>
                        <th class="px-3 py-2">Proveedor</th>
                        <th class="px-3 py-2">Fecha</th>
                        <th class="px-3 py-2">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($compras as $c)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $c->codigocom }}</td>
                            <td class="px-3 py-2 font-semibold">
                                {{ $c->proveedor->nombreprov }}
                            </td>
                            <td class="px-3 py-2">{{ $c->fechacom }}</td>
                            <td class="px-3 py-2">
                                ${{ number_format($c->montototalcom, 2) }}
                            </td>
                        </tr>
                    @endforeach

                    @if($compras->count() === 0)
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                No hay compras registradas
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    @if($modal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded w-full max-w-2xl">

                <h3 class="text-lg font-bold mb-4">
                    Nueva Compra
                </h3>

                {{-- PROVEEDOR --}}
                <select
                    wire:model="idprov"
                    class="w-full mb-3 border rounded px-2 py-1"
                >
                    <option value="">Seleccione proveedor</option>
                    @foreach($proveedores as $p)
                        <option value="{{ $p->idprov }}">
                            {{ $p->nombreprov }}
                        </option>
                    @endforeach
                </select>

                {{-- PRODUCTO --}}
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <select
                        wire:model="idpro"
                        class="border rounded px-2 py-1"
                    >
                        <option value="">Producto</option>
                        @foreach($productos as $pro)
                            <option value="{{ $pro->idpro }}">
                                {{ $pro->nombrepro }}
                            </option>
                        @endforeach
                    </select>

                    <input
                        wire:model.defer="cantidaddetc"
                        type="number"
                        placeholder="Cantidad"
                        class="border rounded px-2 py-1"
                    >

                    <input
                        wire:model.defer="preciocompra"
                        type="number"
                        step="0.01"
                        placeholder="Precio"
                        class="border rounded px-2 py-1"
                    >
                </div>

                <button
                    wire:click="agregarDetalle"
                    class="bg-green-600 text-white px-4 py-2 rounded mb-4"
                >
                    Agregar Producto
                </button>

                {{-- DETALLE --}}
                <table class="min-w-full divide-y divide-gray-200 mb-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2">Producto</th>
                            <th class="px-3 py-2">Cantidad</th>
                            <th class="px-3 py-2">Precio</th>
                            <th class="px-3 py-2 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalles as $index => $d)
                            <tr class="border-b">
                                <td class="px-3 py-2">
                                    {{ $productos->firstWhere('idpro', $d['idpro'])->nombrepro }}
                                </td>
                                <td class="px-3 py-2">{{ $d['cantidaddetc'] }}</td>
                                <td class="px-3 py-2">{{ $d['preciocompra'] }}</td>
                                <td class="px-3 py-2 text-center">
                                    <button
                                        wire:click="eliminarDetalle({{ $index }})"
                                        class="bg-red-600 text-white px-2 py-1 rounded"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        @if(count($detalles) === 0)
                            <tr>
                                <td colspan="4" class="text-center py-3 text-gray-500">
                                    No hay productos agregados
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                {{-- FOOTER --}}
                <div class="flex justify-end gap-2">
                    <button
                        wire:click="cerrarModal"
                        class="px-4 py-2 border rounded"
                    >
                        Cancelar
                    </button>

                    <button
                        wire:click="guardar"
                        class="bg-blue-600 text-white px-4 py-2 rounded"
                    >
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>


