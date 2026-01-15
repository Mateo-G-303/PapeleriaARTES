@extends('admin.layout')

@section('content')
<h1>Panel de Administración</h1>
<p class="lead">Bienvenido, {{ auth()->user()->name }}</p>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5><i class="bi bi-people"></i> Usuarios</h5>
                <p>Gestionar usuarios del sistema</p>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-light">Ir a Usuarios</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5><i class="bi bi-person-badge"></i> Roles</h5>
                <p>Gestionar roles y permisos</p>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-light">Ir a Roles</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning">
            <div class="card-body">
                <h5><i class="bi bi-gear"></i> Configuración</h5>
                <p>Parámetros del sistema</p>
                <a href="{{ route('admin.configuraciones.index') }}" class="btn btn-dark">Ir a Configuración</a>
            </div>
        </div>
    </div>
</div>
@endsection