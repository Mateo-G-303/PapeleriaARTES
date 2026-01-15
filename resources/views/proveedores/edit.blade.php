<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Editar Proveedor</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('proveedores.update', $proveedor->idprov) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">RUC</label>
                    <input type="text" name="rucprov" class="form-control"
                           value="{{ $proveedor->rucprov }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombreprov" class="form-control"
                           value="{{ $proveedor->nombreprov }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correoprov" class="form-control"
                           value="{{ $proveedor->correoprov }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefonoprov" class="form-control"
                           value="{{ $proveedor->telefonoprov }}" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                        ⬅️ Regresar
                    </a>

                    <button type="submit" class="btn btn-success">
                        💾 Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
