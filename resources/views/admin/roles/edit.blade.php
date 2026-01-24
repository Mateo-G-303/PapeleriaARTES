@extends('admin.layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Editar Rol: {{ $rol->nombrerol }}</h4>
                @if($rol->idrol === 1)
                    <span class="badge bg-warning text-dark">Rol del Sistema</span>
                @endif
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.update', $rol->idrol) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="nombrerol" class="form-label">Nombre del Rol *</label>
                                <input type="text" class="form-control @error('nombrerol') is-invalid @enderror" 
                                       id="nombrerol" name="nombrerol" 
                                       value="{{ old('nombrerol', $rol->nombrerol) }}" 
                                       required maxlength="30"
                                       {{ $rol->idrol === 1 ? 'readonly' : '' }}>
                                @error('nombrerol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="descripcionrol" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="descripcionrol" name="descripcionrol" 
                                       value="{{ old('descripcionrol', $rol->descripcionrol) }}" maxlength="150">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" class="form-check-input" id="estadorol" name="estadorol" 
                                           {{ $rol->estadorol ? 'checked' : '' }}
                                           {{ $rol->idrol === 1 ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="estadorol">Activo</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    
                    @if($rol->idrol === 1)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> El rol <strong>Administrador</strong> tiene todos los permisos del sistema y no pueden ser modificados.
                        </div>
                    @else
                        <h5 class="mb-3">
                            <i class="bi bi-shield-check"></i> Permisos del Rol
                            <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="seleccionarTodos()">Seleccionar todos</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="deseleccionarTodos()">Deseleccionar todos</button>
                        </h5>

                        <div class="row">
                            @foreach($permisosAgrupados as $modulo => $permisos)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light py-2">
                                        <strong>
                                            @switch($modulo)
                                                @case('Dashboard') <i class="bi bi-speedometer2"></i> @break
                                                @case('Productos') <i class="bi bi-box-seam"></i> @break
                                                @case('Categorias') <i class="bi bi-tags"></i> @break
                                                @case('Proveedores') <i class="bi bi-truck"></i> @break
                                                @case('Ventas') <i class="bi bi-cart"></i> @break
                                                @case('Usuarios') <i class="bi bi-people"></i> @break
                                                @case('Roles') <i class="bi bi-person-badge"></i> @break
                                                @case('Configuraciones') <i class="bi bi-gear"></i> @break
                                                @default <i class="bi bi-folder"></i>
                                            @endswitch
                                            {{ $modulo }}
                                        </strong>
                                        <div class="form-check float-end">
                                            <input type="checkbox" class="form-check-input modulo-check" data-modulo="{{ $modulo }}" onclick="toggleModulo('{{ $modulo }}')">
                                        </div>
                                    </div>
                                    <div class="card-body py-2">
                                        @foreach($permisos as $permiso)
                                        <div class="form-check">
                                            <input class="form-check-input permiso-check permiso-{{ $modulo }}" 
                                                   type="checkbox" 
                                                   name="permisos[]" 
                                                   value="{{ $permiso->idper }}" 
                                                   id="permiso_{{ $permiso->idper }}"
                                                   {{ in_array($permiso->idper, old('permisos', $permisosRol)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permiso_{{ $permiso->idper }}">
                                                {{ $permiso->descripcionper }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Actualizar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($rol->idrol !== 1)
<script>
function toggleModulo(modulo) {
    const checkboxModulo = document.querySelector(`[data-modulo="${modulo}"]`);
    const checkboxes = document.querySelectorAll(`.permiso-${modulo}`);
    checkboxes.forEach(cb => cb.checked = checkboxModulo.checked);
}

function seleccionarTodos() {
    document.querySelectorAll('.permiso-check, .modulo-check').forEach(cb => cb.checked = true);
}

function deseleccionarTodos() {
    document.querySelectorAll('.permiso-check, .modulo-check').forEach(cb => cb.checked = false);
}

// Inicializar checkboxes de módulo
document.addEventListener('DOMContentLoaded', function() {
    @foreach($permisosAgrupados as $modulo => $permisos)
        (function() {
            const checkboxModulo = document.querySelector('[data-modulo="{{ $modulo }}"]');
            const checkboxesModulo = document.querySelectorAll('.permiso-{{ $modulo }}');
            if (checkboxModulo && checkboxesModulo.length > 0) {
                const todosSeleccionados = Array.from(checkboxesModulo).every(c => c.checked);
                checkboxModulo.checked = todosSeleccionados;
            }
        })();
    @endforeach
});

// Actualizar checkbox de módulo cuando se cambian los permisos individuales
document.querySelectorAll('.permiso-check').forEach(cb => {
    cb.addEventListener('change', function() {
        const classes = Array.from(this.classList);
        const moduloClass = classes.find(c => c.startsWith('permiso-') && c !== 'permiso-check');
        if (moduloClass) {
            const modulo = moduloClass.replace('permiso-', '');
            const checkboxModulo = document.querySelector(`[data-modulo="${modulo}"]`);
            const checkboxesModulo = document.querySelectorAll(`.${moduloClass}`);
            const todosSeleccionados = Array.from(checkboxesModulo).every(c => c.checked);
            if (checkboxModulo) checkboxModulo.checked = todosSeleccionados;
        }
    });
});
</script>
@endif
@endsection