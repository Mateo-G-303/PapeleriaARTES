<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
            ⬅️ Regresar
        </a>
    </div>


    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Proveedores</h5>
            @if(Auth::user()->tienePermiso('proveedores.crear'))
            <a href="{{ route('proveedores.create') }}" class="btn btn-light btn-sm">
                ➕ Nuevo Proveedor
            </a>
            @endif
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>RUC</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($proveedores as $p)
                    <tr>
                        <td>{{ $p->rucprov }}</td>
                        <td>{{ $p->nombreprov }}</td>
                        <td>{{ $p->correoprov }}</td>
                        <td>{{ $p->telefonoprov }}</td>
                        <td class="d-flex justify-content-center gap-2">

                            @if(Auth::user()->tienePermiso('proveedores.editar'))
                            <a href="{{ route('proveedores.edit', $p->idprov) }}" 
                               class="btn btn-warning btn-sm">
                                ✏️ Editar
                            </a>
                            @endif

                            @if(Auth::user()->tienePermiso('proveedores.eliminar'))
                            <form action="{{ route('proveedores.destroy', $p->idprov) }}" 
                                  method="POST"
                                  onsubmit="return confirm('¿Está seguro de eliminar este proveedor?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    🗑️ Eliminar
                                </button>
                            </form>
                            @endif

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>