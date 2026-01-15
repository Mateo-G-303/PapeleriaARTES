@extends('admin.layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Parámetros de Sesión y Bloqueo</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.configuraciones.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @php
                        $sessionLifetime = $configuraciones->where('clave', 'session_lifetime')->first()->valor ?? 120;
                        $maxAttempts = $configuraciones->where('clave', 'max_login_attempts')->first()->valor ?? 5;
                    @endphp

                    <div class="mb-3">
                        <label for="session_lifetime" class="form-label">Tiempo de Sesión (minutos) *</label>
                        <input type="number" class="form-control" id="session_lifetime" name="session_lifetime" value="{{ $sessionLifetime }}" min="1" max="1440" required>
                        <small class="text-muted">Tiempo máximo de inactividad antes de cerrar sesión (1-1440 minutos)</small>
                    </div>

                    <div class="mb-3">
                        <label for="max_login_attempts" class="form-label">Intentos Máximos de Login *</label>
                        <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts" value="{{ $maxAttempts }}" min="1" max="10" required>
                        <small class="text-muted">Número de intentos fallidos antes de bloquear la cuenta (1-10)</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Configuración</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection