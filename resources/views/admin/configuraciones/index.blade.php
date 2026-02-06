@extends('admin.layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        {{-- Tarjeta de IVA --}}
        <div class="card mb-4">
            <div class="card-header">
                <h4><i class="bi bi-percent"></i> Configuración de IVA</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.configuraciones.iva') }}" method="POST">
                    @csrf
                    
                    @php
                        $ivaPorcentaje = $configuraciones->where('clave', 'iva_porcentaje')->first()->valor ?? 12;
                    @endphp

                    <div class="mb-3">
                        <label for="iva_porcentaje" class="form-label">Porcentaje de IVA *</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="iva_porcentaje" name="iva_porcentaje" value="{{ $ivaPorcentaje }}" min="0" max="100" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Este porcentaje se aplicará a todas las ventas</small>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg"></i> Guardar IVA
                    </button>
                </form>
            </div>
        </div>

        {{-- Tarjeta de Sesión y Bloqueo --}}
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-shield-lock"></i> Parámetros de Sesión y Bloqueo</h4>
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

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Configuración
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection