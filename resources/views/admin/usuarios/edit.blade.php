@extends('admin.layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Editar Usuario</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" value="{{ $usuario->cedula }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="idrol" class="form-label">Rol *</label>
                            <select class="form-select" id="idrol" name="idrol" required>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->idrol }}" {{ $usuario->idrol == $rol->idrol ? 'selected' : '' }}>
                                        {{ $rol->nombrerol }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $usuario->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="8">
                        <small class="text-muted">Dejar vacío para mantener la actual</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection