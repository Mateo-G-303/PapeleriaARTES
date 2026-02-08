@extends('admin.layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4>Crear Nuevo Rol</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombrerol" class="form-label">Nombre del Rol *</label>
                                <input type="text" class="form-control @error('nombrerol') is-invalid @enderror" id="nombrerol" name="nombrerol" value="{{ old('nombrerol') }}" required maxlength="30">
                                @error('nombrerol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="descripcionrol" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="descripcionrol" name="descripcionrol" value="{{ old('descripcionrol') }}" maxlength="150">
                            </div>
                        </div>
                    </div>

                    <hr>
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
                                               {{ in_array($permiso->idper, old('permisos', [])) ? 'checked' : '' }}>
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

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Guardar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

// Actualizar checkbox de módulo cuando se cambian los permisos individuales
document.querySelectorAll('.permiso-check').forEach(cb => {
    cb.addEventListener('change', function() {
        const modulo = this.classList[2].replace('permiso-', '');
        const checkboxModulo = document.querySelector(`[data-modulo="${modulo}"]`);
        const checkboxesModulo = document.querySelectorAll(`.permiso-${modulo}`);
        const todosSeleccionados = Array.from(checkboxesModulo).every(c => c.checked);
        checkboxModulo.checked = todosSeleccionados;
    });
});
</script>
@endsection