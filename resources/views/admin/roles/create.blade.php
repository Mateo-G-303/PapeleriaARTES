@extends('admin.layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Crear Nuevo Rol</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombrerol" class="form-label">Nombre del Rol *</label>
                        <input type="text" class="form-control" id="nombrerol" name="nombrerol" value="{{ old('nombrerol') }}" required maxlength="30">
                    </div>
                    <div class="mb-3">
                        <label for="descripcionrol" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcionrol" name="descripcionrol" rows="3" maxlength="150">{{ old('descripcionrol') }}</textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection