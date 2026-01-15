<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nueva Venta - Papelería ARTES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .producto-row { transition: background-color 0.3s; }
        .producto-row:hover { background-color: #f8f9fa; }
        .stock-bajo { color: #dc3545; font-weight: bold; }
        .scanner-input { font-size: 1.5rem; text-align: center; letter-spacing: 2px; }
        .total-display { font-size: 2rem; font-weight: bold; color: #198754; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">Papelería ARTES - Punto de Venta</span>
            <a href="{{ route('ventas.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Panel de escaneo -->
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-upc-scan"></i> Escanear Producto</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <input type="text" id="codigoBarras" class="form-control scanner-input" 
                                    placeholder="Escanee o ingrese código de barras" autofocus>
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="btnBuscar" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div id="mensajeProducto" class="mt-3"></div>
                    </div>
                </div>

                <!-- Lista de productos -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-cart3"></i> Productos en la Venta</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Precio Unit.</th>
                                    <th width="120">Cantidad</th>
                                    <th>Subtotal</th>
                                    <th width="60">Quitar</th>
                                </tr>
                            </thead>
                            <tbody id="tablaProductos">
                                <tr id="sinProductos">
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mb-0">Escanee productos para agregarlos</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Panel de resumen -->
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Resumen de Venta</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Productos:</span>
                            <span id="totalProductos">0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Artículos:</span>
                            <span id="totalArticulos">0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-4">TOTAL:</span>
                            <span class="total-display">$<span id="totalVenta">0.00</span></span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="btnProcesarVenta" class="btn btn-success btn-lg w-100 mb-2" disabled>
                            <i class="bi bi-check-circle"></i> Procesar Venta
                        </button>
                        <button type="button" id="btnCancelar" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle"></i> Cancelar Venta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de venta exitosa -->
    <div class="modal fade" id="modalExito" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle"></i> Venta Exitosa</h5>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">¡Venta registrada correctamente!</h4>
                    <p>Venta #<span id="ventaId"></span></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <a href="#" id="btnVerFactura" class="btn btn-primary" target="_blank">
                        <i class="bi bi-file-pdf"></i> Ver Factura
                    </a>
                    <a href="#" id="btnDescargarFactura" class="btn btn-success">
                        <i class="bi bi-download"></i> Descargar PDF
                    </a>
                    <button type="button" id="btnNuevaVenta" class="btn btn-secondary">
                        <i class="bi bi-plus"></i> Nueva Venta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let productosVenta = [];

        document.addEventListener('DOMContentLoaded', function() {
            const codigoInput = document.getElementById('codigoBarras');
            const btnBuscar = document.getElementById('btnBuscar');
            const btnProcesar = document.getElementById('btnProcesarVenta');
            const btnCancelar = document.getElementById('btnCancelar');

            // Buscar al presionar Enter
            codigoInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    buscarProducto();
                }
            });

            btnBuscar.addEventListener('click', buscarProducto);
            btnProcesar.addEventListener('click', procesarVenta);
            btnCancelar.addEventListener('click', cancelarVenta);

            document.getElementById('btnNuevaVenta').addEventListener('click', function() {
                location.reload();
            });
        });

        function buscarProducto() {
            const codigo = document.getElementById('codigoBarras').value.trim();
            if (!codigo) return;

            fetch('{{ route("ventas.buscar-producto") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ codigo: codigo })
            })
            .then(response => response.json())
            .then(data => {
                const mensajeDiv = document.getElementById('mensajeProducto');
                
                if (data.success) {
                    agregarProducto(data.producto);
                    mensajeDiv.innerHTML = `<div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> ${data.producto.nombre} agregado
                    </div>`;
                } else {
                    mensajeDiv.innerHTML = `<div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i> ${data.message}
                    </div>`;
                }

                document.getElementById('codigoBarras').value = '';
                document.getElementById('codigoBarras').focus();

                setTimeout(() => { mensajeDiv.innerHTML = ''; }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function agregarProducto(producto) {
            // Verificar si ya existe
            const existente = productosVenta.find(p => p.idpro === producto.idpro);
            
            if (existente) {
                if (existente.cantidad < producto.stockDisponible) {
                    existente.cantidad++;
                } else {
                    alert('No hay más stock disponible para este producto');
                    return;
                }
            } else {
                productosVenta.push({
                    idpro: producto.idpro,
                    codigo: producto.codigo,
                    nombre: producto.nombre,
                    precio: parseFloat(producto.precio),
                    cantidad: 1,
                    stockDisponible: producto.stockDisponible
                });
            }

            actualizarTabla();
        }

        function actualizarTabla() {
            const tbody = document.getElementById('tablaProductos');
            
            if (productosVenta.length === 0) {
                tbody.innerHTML = `<tr id="sinProductos">
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mb-0">Escanee productos para agregarlos</p>
                    </td>
                </tr>`;
                document.getElementById('btnProcesarVenta').disabled = true;
            } else {
                tbody.innerHTML = productosVenta.map((p, index) => `
                    <tr class="producto-row">
                        <td>${p.codigo}</td>
                        <td>${p.nombre}</td>
                        <td>$${p.precio.toFixed(2)}</td>
                        <td>
                            <div class="input-group input-group-sm">
                                <button class="btn btn-outline-secondary" onclick="cambiarCantidad(${index}, -1)">-</button>
                                <input type="number" class="form-control text-center" value="${p.cantidad}" 
                                    min="1" max="${p.stockDisponible}" 
                                    onchange="setCantidad(${index}, this.value)" style="width: 60px;">
                                <button class="btn btn-outline-secondary" onclick="cambiarCantidad(${index}, 1)">+</button>
                            </div>
                            <small class="text-muted">Disp: ${p.stockDisponible}</small>
                        </td>
                        <td>$${(p.precio * p.cantidad).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-danger" onclick="quitarProducto(${index})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
                document.getElementById('btnProcesarVenta').disabled = false;
            }

            actualizarTotales();
        }

        function cambiarCantidad(index, delta) {
            const producto = productosVenta[index];
            const nuevaCantidad = producto.cantidad + delta;
            
            if (nuevaCantidad >= 1 && nuevaCantidad <= producto.stockDisponible) {
                producto.cantidad = nuevaCantidad;
                actualizarTabla();
            }
        }

        function setCantidad(index, valor) {
            const producto = productosVenta[index];
            const nuevaCantidad = parseInt(valor);
            
            if (nuevaCantidad >= 1 && nuevaCantidad <= producto.stockDisponible) {
                producto.cantidad = nuevaCantidad;
            } else if (nuevaCantidad > producto.stockDisponible) {
                producto.cantidad = producto.stockDisponible;
            } else {
                producto.cantidad = 1;
            }
            actualizarTabla();
        }

        function quitarProducto(index) {
            productosVenta.splice(index, 1);
            actualizarTabla();
        }

        function actualizarTotales() {
            const totalProductos = productosVenta.length;
            const totalArticulos = productosVenta.reduce((sum, p) => sum + p.cantidad, 0);
            const totalVenta = productosVenta.reduce((sum, p) => sum + (p.precio * p.cantidad), 0);

            document.getElementById('totalProductos').textContent = totalProductos;
            document.getElementById('totalArticulos').textContent = totalArticulos;
            document.getElementById('totalVenta').textContent = totalVenta.toFixed(2);
        }

        function procesarVenta() {
            if (productosVenta.length === 0) return;

            const datos = {
                productos: productosVenta.map(p => ({
                    idpro: p.idpro,
                    cantidad: p.cantidad,
                    precio: p.precio
                }))
            };

            fetch('{{ route("ventas.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('ventaId').textContent = data.venta_id;
                    document.getElementById('btnVerFactura').href = `/ventas/${data.venta_id}/factura/ver`;
                    document.getElementById('btnDescargarFactura').href = `/ventas/${data.venta_id}/factura`;
                    
                    new bootstrap.Modal(document.getElementById('modalExito')).show();
                    productosVenta = [];
                    actualizarTabla();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la venta');
            });
        }

        function cancelarVenta() {
            if (productosVenta.length === 0 || confirm('¿Está seguro de cancelar la venta?')) {
                productosVenta = [];
                actualizarTabla();
                document.getElementById('codigoBarras').focus();
            }
        }
    </script>
</body>
</html>