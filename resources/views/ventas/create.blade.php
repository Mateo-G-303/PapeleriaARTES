<x-layouts.app.sidebar title="Nueva Venta">
    <flux:main>
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">Nueva Venta</h1>
 
                <!-- Scanner de Código de Barras -->
                <div style="background-color: #f9fafb; border: 2px solid #2b2c2eff; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem;" class="dark:bg-zinc-800 dark:border-zinc-600">
                    <h2 style="color: #1f2937; font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;" class="dark:text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.25rem; width: 1.25rem; color: #7c3aed;" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z" clip-rule="evenodd" />
                        </svg>
                        Escanear Producto
                    </h2>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;" class="dark:text-gray-200">
                            Código de Barras
                        </label>
                        <input 
                            type="text" 
                            id="codigoBarras" 
                            placeholder="Escanee o escriba el código..."
                            style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #23262cff; border-radius: 0.5rem; background-color: white; color: #1f2937;"
                            class="dark:bg-zinc-900 dark:text-white dark:border-zinc-500"
                            autofocus
                        >
                    </div>
                </div>

                <!-- Tabla de Productos - CABECERA MORADA -->
                <div style="background-color: #f9fafb; border: 2px solid #d1d5db; border-radius: 0.75rem; margin-bottom: 1.5rem; overflow: hidden;" class="dark:bg-zinc-800 dark:border-zinc-600">
                    <div style="background-color: #7c3aed; color: white; padding: 1rem;">
                        <h2 style="font-size: 1.25rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Productos en Carrito
                        </h2>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="min-width: 100%;">
                            <thead style="background-color: #e5e7eb;" class="dark:bg-zinc-900">
                                <tr>
                                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">Código</th>
                                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">Producto</th>
                                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">Precio</th>
                                    <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">Cantidad</th>
                                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">Subtotal</th>
                                    <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.75rem; font-weight: 700; color: #374151; text-transform: uppercase;" class="dark:text-gray-200">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaProductos" style="background-color: white;" class="dark:bg-zinc-800">
                                <tr id="mensajeVacio">
                                    <td colspan="6" style="padding: 2rem; text-align: center;">
                                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; color: #6b7280;">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 2.5rem; width: 2.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <p style="font-size: 1rem; font-weight: 500; color: #374151;" class="dark:text-gray-300">No hay productos agregados</p>
                                            <p style="font-size: 0.875rem; color: #6b7280;" class="dark:text-gray-400">Escanee un código de barras</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Resumen de Venta -->
                <div style="background-color: #f9fafb; border: 2px solid #d1d5db; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem;" class="dark:bg-zinc-800 dark:border-zinc-600">
                    <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;" class="dark:text-white">Resumen de Venta</h2>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; justify-content: space-between; font-size: 1.125rem;">
                            <span style="font-weight: 600; color: #374151;" class="dark:text-gray-200">Subtotal:</span>
                            <span id="subtotal" style="font-weight: 700; color: #1f2937;" class="dark:text-white">$0.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.125rem;">
                            <span style="font-weight: 600; color: #374151;" class="dark:text-gray-200">IVA ({{ number_format($ivaActual, 2) }}%):</span>
                            <span id="ivaValor" style="font-weight: 700; color: #1f2937;" class="dark:text-white">$0.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.5rem; font-weight: 700; border-top: 2px solid #d1d5db; padding-top: 1rem; margin-top: 1rem;">
                            <span style="color: #1f2937;" class="dark:text-white">TOTAL:</span>
                            <span id="total" style="color: #16a34a;">$0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción - MORADO -->
                <div style="display: flex; gap: 1rem;">
                    <button 
                        id="btnFinalizarVenta"
                        style="flex: 1; background-color: #9ca3af; color: white; font-weight: 700; padding: 1rem 1.5rem; border-radius: 0.75rem; border: none; cursor: not-allowed; display: flex; align-items: center; justify-content: center; gap: 0.5rem;"
                        disabled
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Finalizar Venta
                    </button>
                    <a 
                        href="{{ route('ventas.index') }}" 
                        style="flex: 1; background-color: #4b5563; color: white; font-weight: 700; padding: 1rem 1.5rem; border-radius: 0.75rem; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.5rem;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </a>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación - NARANJA/TOMATE - OCULTO POR DEFECTO -->
        <div id="modalConfirmacion" style="display: none; position: fixed; inset: 0; background-color: rgba(0,0,0,0.6); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
            <div style="background-color: #fff7ed; border: 4px solid #f97316; border-radius: 1rem; padding: 2rem; max-width: 28rem; width: 100%;">
                <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1f2937; text-align: center;">Seleccione tipo de factura</h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <button 
                        id="btnFacturaCompleta"
                        style="width: 100%; background-color: #f97316; color: white; font-weight: 700; padding: 1rem 1.5rem; border-radius: 0.75rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Factura con Datos del Cliente
                    </button>
                    
                    <button 
                        id="btnConsumidorFinal"
                        style="width: 100%; background-color: #f97316; color: white; font-weight: 700; padding: 1rem 1.5rem; border-radius: 0.75rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                        Consumidor Final
                    </button>
                    
                    <button 
                        id="btnCancelarModal"
                        style="width: 100%; background-color: #4b5563; color: white; font-weight: 700; padding: 1rem 1.5rem; border-radius: 0.75rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Datos del Cliente - VERDE - OCULTO POR DEFECTO -->
        <div id="modalCliente" style="display: none; position: fixed; inset: 0; background-color: rgba(0,0,0,0.6); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
            <div style="background-color: #f0fdf4; border: 4px solid #22c55e; border-radius: 1rem; padding: 2rem; max-width: 42rem; width: 100%; max-height: 90vh; overflow-y: auto;">
                <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1f2937; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.75rem; width: 1.75rem; color: #22c55e;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Datos del Cliente
                </h3>
                
                <form id="formCliente" style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">RUC / Identificación *</label>
                        <input 
                            type="text" 
                            name="cliente_ruc" 
                            required
                            maxlength="13"
                            style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #9ca3af; border-radius: 0.75rem; background-color: white; color: #1f2937;"
                            placeholder="Ingrese RUC o cédula"
                        >
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">Nombre / Razón Social *</label>
                        <input 
                            type="text" 
                            name="cliente_nombre" 
                            required
                            maxlength="255"
                            style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #9ca3af; border-radius: 0.75rem; background-color: white; color: #1f2937;"
                            placeholder="Nombre completo o razón social"
                        >
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">Dirección</label>
                        <input 
                            type="text" 
                            name="cliente_direccion"
                            maxlength="255"
                            style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #9ca3af; border-radius: 0.75rem; background-color: white; color: #1f2937;"
                            placeholder="Dirección del cliente"
                        >
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">Teléfono</label>
                            <input 
                                type="text" 
                                name="cliente_telefono"
                                maxlength="20"
                                style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #9ca3af; border-radius: 0.75rem; background-color: white; color: #1f2937;"
                                placeholder="Teléfono"
                            >
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">Email</label>
                            <input 
                                type="email" 
                                name="cliente_email"
                                maxlength="100"
                                style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #9ca3af; border-radius: 0.75rem; background-color: white; color: #1f2937;"
                                placeholder="correo@ejemplo.com"
                            >
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 1rem; padding-top: 1rem;">
                        <button 
                            type="submit"
                            style="flex: 1; background-color: #22c55e; color: white; font-weight: 700; padding: 1rem 1.5rem; border-radius: 0.75rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Confirmar Venta
                        </button>
                        <button 
                            type="button"
                            id="btnCancelarCliente"
                            style="flex: 1; background-color: #4b5563; color: white; font-weight: 700; padding: 1rem 1.5rem; border-radius: 0.75rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        const IVA_PORCENTAJE = {{ $ivaActual }};
        let productosVenta = [];

        document.getElementById('codigoBarras').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarProducto(this.value);
                this.value = '';
            }
        });

        function buscarProducto(codigo) {
            if (!codigo.trim()) return;
            
            fetch('{{ route("ventas.buscar-producto") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ codigo: codigo })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    agregarProducto(data.producto);
                } else {
                    mostrarAlerta(data.message, 'error');
                }
            })
            .catch(error => {
                mostrarAlerta('Error al buscar el producto', 'error');
            });
        }

        function mostrarAlerta(mensaje, tipo) {
            const alerta = document.createElement('div');
            if (tipo === 'error' || tipo === 'warning') {
                alerta.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background-color: #fecdd3; border: 2px solid #f43f5e; color: #9f1239; padding: 1rem 1.5rem; border-radius: 0.75rem; font-weight: 700; z-index: 9999; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
            } else {
                alerta.style.cssText = 'position: fixed; top: 1rem; right: 1rem; background-color: #bbf7d0; border: 2px solid #22c55e; color: #166534; padding: 1rem 1.5rem; border-radius: 0.75rem; font-weight: 700; z-index: 9999; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
            }
            alerta.textContent = mensaje;
            document.body.appendChild(alerta);
            
            setTimeout(function() {
                alerta.remove();
            }, 3000);
        }

        function agregarProducto(producto) {
            var existe = productosVenta.find(function(p) { return p.idpro === producto.idpro; });
            
            if (existe) {
                if (existe.cantidad < existe.stockDisponible) {
                    existe.cantidad++;
                    existe.subtotal = existe.cantidad * existe.precio;
                } else {
                    mostrarAlerta('Stock máximo: ' + existe.stockDisponible + ' unidades', 'warning');
                    return;
                }
            } else {
                if (producto.stockDisponible <= 0) {
                    mostrarAlerta('No hay stock disponible para este producto', 'error');
                    return;
                }
                productosVenta.push({
                    idpro: producto.idpro,
                    codigo: producto.codigo,
                    nombre: producto.nombre,
                    precio: parseFloat(producto.precio),
                    cantidad: 1,
                    stockDisponible: producto.stockDisponible,
                    subtotal: parseFloat(producto.precio)
                });
            }
            
            renderizarTabla();
            calcularTotales();
        }

        function renderizarTabla() {
            var tbody = document.getElementById('tablaProductos');
            var btn = document.getElementById('btnFinalizarVenta');
            
            if (productosVenta.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">No hay productos agregados</td></tr>';
                btn.disabled = true;
                btn.style.backgroundColor = '#9ca3af';
                btn.style.cursor = 'not-allowed';
                return;
            }
            
            btn.disabled = false;
            btn.style.backgroundColor = '#7c3aed';
            btn.style.cursor = 'pointer';
            
            var html = '';
            for (var i = 0; i < productosVenta.length; i++) {
                var p = productosVenta[i];
                html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
                html += '<td style="padding: 0.75rem 1rem;"><span style="background-color: #e5e7eb; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-family: monospace; font-weight: 700; color: #374151;">' + p.codigo + '</span></td>';
                html += '<td style="padding: 0.75rem 1rem;"><div style="font-weight: 700; color: #374151;">' + p.nombre + '</div><div style="font-size: 0.75rem; color: #7c3aed;">Stock: ' + p.stockDisponible + '</div></td>';
                html += '<td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #374151;">$' + p.precio.toFixed(2) + '</td>';
                html += '<td style="padding: 0.75rem 1rem; text-align: center;"><div style="display: flex; align-items: center; justify-content: center; gap: 0.25rem;">';
                html += '<button onclick="cambiarCantidad(' + i + ', -1)" style="width: 2rem; height: 2rem; background-color: #dc2626; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 700;">−</button>';
                html += '<input type="number" value="' + p.cantidad + '" min="1" max="' + p.stockDisponible + '" onchange="actualizarCantidad(' + i + ', this.value)" style="width: 3.5rem; height: 2rem; text-align: center; border: 2px solid #9ca3af; border-radius: 0.375rem; font-weight: 700; color: #374151;">';
                html += '<button onclick="cambiarCantidad(' + i + ', 1)" style="width: 2rem; height: 2rem; background-color: #16a34a; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 700;">+</button>';
                html += '</div></td>';
                html += '<td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #16a34a;">$' + p.subtotal.toFixed(2) + '</td>';
                html += '<td style="padding: 0.75rem 1rem; text-align: center;"><button onclick="eliminarProducto(' + i + ')" style="background-color: #dc2626; color: white; padding: 0.375rem 0.75rem; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 700;">Eliminar</button></td>';
                html += '</tr>';
            }
            tbody.innerHTML = html;
        }

        function actualizarCantidad(index, valor) {
            var producto = productosVenta[index];
            var nuevaCantidad = parseInt(valor);
            if (isNaN(nuevaCantidad) || nuevaCantidad < 1) nuevaCantidad = 1;
            if (nuevaCantidad > producto.stockDisponible) {
                mostrarAlerta('Stock máximo: ' + producto.stockDisponible, 'warning');
                nuevaCantidad = producto.stockDisponible;
            }
            producto.cantidad = nuevaCantidad;
            producto.subtotal = producto.cantidad * producto.precio;
            renderizarTabla();
            calcularTotales();
        }

        function cambiarCantidad(index, cambio) {
            var producto = productosVenta[index];
            var nuevaCantidad = producto.cantidad + cambio;
            if (nuevaCantidad <= 0) { eliminarProducto(index); return; }
            if (nuevaCantidad > producto.stockDisponible) {
                mostrarAlerta('Stock máximo: ' + producto.stockDisponible, 'warning');
                return;
            }
            producto.cantidad = nuevaCantidad;
            producto.subtotal = producto.cantidad * producto.precio;
            renderizarTabla();
            calcularTotales();
        }

        function eliminarProducto(index) {
            productosVenta.splice(index, 1);
            renderizarTabla();
            calcularTotales();
        }

        function calcularTotales() {
            var subtotal = 0;
            for (var i = 0; i < productosVenta.length; i++) {
                subtotal += productosVenta[i].subtotal;
            }
            var ivaValor = subtotal * (IVA_PORCENTAJE / 100);
            var total = subtotal + ivaValor;
            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('ivaValor').textContent = '$' + ivaValor.toFixed(2);
            document.getElementById('total').textContent = '$' + total.toFixed(2);
        }

        document.getElementById('btnFinalizarVenta').addEventListener('click', function() {
            if (productosVenta.length === 0) return;
            document.getElementById('modalConfirmacion').style.display = 'flex';
        });

        document.getElementById('btnConsumidorFinal').addEventListener('click', function() {
            procesarVenta('consumidor_final', {});
        });

        document.getElementById('btnFacturaCompleta').addEventListener('click', function() {
            document.getElementById('modalConfirmacion').style.display = 'none';
            document.getElementById('modalCliente').style.display = 'flex';
        });

        document.getElementById('btnCancelarModal').addEventListener('click', function() {
            document.getElementById('modalConfirmacion').style.display = 'none';
        });

        document.getElementById('btnCancelarCliente').addEventListener('click', function() {
            document.getElementById('modalCliente').style.display = 'none';
        });

        document.getElementById('formCliente').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var datos = {};
            formData.forEach(function(value, key) {
                datos[key] = value;
            });
            procesarVenta('factura', datos);
        });

        function procesarVenta(tipoFactura, datosCliente) {
            var productosEnviar = [];
            for (var i = 0; i < productosVenta.length; i++) {
                productosEnviar.push({
                    idpro: productosVenta[i].idpro,
                    cantidad: productosVenta[i].cantidad,
                    precio: productosVenta[i].precio
                });
            }
            
            var body = {
                tipo_factura: tipoFactura,
                productos: productosEnviar
            };
            
            for (var key in datosCliente) {
                body[key] = datosCliente[key];
            }
            
            fetch('{{ route("ventas.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(body)
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    mostrarAlerta('Venta registrada exitosamente', 'success');
                    setTimeout(function() { 
                        window.location.href = '{{ url("/ventas") }}/' + data.venta_id; 
                    }, 1000);
                } else {
                    mostrarAlerta('Error: ' + data.message, 'error');
                }
            })
            .catch(function() { 
                mostrarAlerta('Error al procesar', 'error'); 
            })
            .finally(function() {
                document.getElementById('modalConfirmacion').style.display = 'none';
                document.getElementById('modalCliente').style.display = 'none';
            });
        }

        // Cerrar modales al hacer clic fuera
        document.getElementById('modalConfirmacion').addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
        
        document.getElementById('modalCliente').addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
        </script>
    </flux:main>
</x-layouts.app.sidebar>