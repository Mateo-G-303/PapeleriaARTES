@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person-badge"></i> Gestión de Roles</h1>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Rol
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%">Nombre</th>
                    <th width="25%">Descripción</th>
                    <th width="20%">Permisos</th>
                    <th width="10%">Usuarios</th>
                    <th width="10%">Estado</th>
                    <th width="15%">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $rol)
                <tr>
                    <td>{{ $rol->idrol }}</td>
                    <td>
                        <strong>{{ $rol->nombrerol }}</strong>
                        @if($rol->idrol === 1)
                            <span class="badge bg-warning text-dark ms-1" title="Rol del sistema">
                                <i class="bi bi-star-fill"></i>
                            </span>
                        @endif
                    </td>
                    <td>{{ $rol->descripcionrol ?? '-' }}</td>
                    <td>
                        @if($rol->idrol === 1)
                            <span class="badge bg-primary">Todos los permisos</span>
                        @else
                            <span class="badge bg-info">{{ $rol->permisos->count() }} permisos</span>
                            <button type="button" class="btn btn-sm btn-link p-0 ms-1" 
                                    data-bs-toggle="modal" data-bs-target="#modalPermisos{{ $rol->idrol }}"
                                    title="Ver permisos">
                                <i class="bi bi-eye"></i>
                            </button>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ $rol->users->count() }}</span>
                    </td>
                    <td>
                        @if($rol->estadorol)
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Activo</span>
                        @else
                            <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.roles.edit', $rol->idrol) }}" class="btn btn-sm btn-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($rol->idrol !== 1)
                            <form action="{{ route('admin.roles.toggle', $rol->idrol) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $rol->estadorol ? 'btn-secondary' : 'btn-success' }}" 
                                        title="{{ $rol->estadorol ? 'Desactivar' : 'Activar' }}">
                                    <i class="bi {{ $rol->estadorol ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                </button>
                            </form>
                        @endif
                        @if(!in_array($rol->idrol, [1, 2, 3]))
                            <form action="{{ route('admin.roles.destroy', $rol->idrol) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('¿Está seguro de eliminar este rol?')" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2">No hay roles registrados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modales para ver permisos de cada rol -->
@foreach($roles as $rol)
@if($rol->idrol !== 1)
<div class="modal fade" id="modalPermisos{{ $rol->idrol }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-shield-check"></i> Permisos de: {{ $rol->nombrerol }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($rol->permisos->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($rol->permisos as $permiso)
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success"></i>
                                {{ $permiso->descripcionper }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted text-center mb-0">Este rol no tiene permisos asignados</p>
                @endif
            </div>
            <div class="modal-footer">
                <a href="{{ route('admin.roles.edit', $rol->idrol) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Editar Permisos
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection