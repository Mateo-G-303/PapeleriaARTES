@extends('admin.layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
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

        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-database"></i> Respaldos del Sistema</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-code text-primary" style="font-size: 2.5rem;"></i>
                                <h5 class="mt-3">Backup Técnico</h5>
                                <p class="text-muted small">Archivo .SQL completo para restauración del sistema</p>
                                <a href="{{ route('admin.configuraciones.backup') }}" class="btn btn-primary">
                                    <i class="bi bi-download"></i> Descargar .SQL
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-excel text-success" style="font-size: 2.5rem;"></i>
                                <h5 class="mt-3">Exportar Datos</h5>
                                <p class="text-muted small">Excel con todos los datos del negocio</p>
                                <a href="{{ route('admin.configuraciones.exportar-datos') }}" class="btn btn-success">
                                    <i class="bi bi-download"></i> Descargar Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection