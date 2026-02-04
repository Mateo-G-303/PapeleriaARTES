<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        // Solo aplicar si hay usuario autenticado
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Verificar que el usuario tenga relación con rol
        if (!$user->rol) {
            return $next($request);
        }

        // No aplicar timeout al administrador
        if ($user->rol->nombrerol === 'Administrador') {
            return $next($request);
        }

        // Verificar si existe la tabla configuraciones
        try {
            $sessionLifetime = (int) \App\Models\Configuracion::obtener('session_lifetime', 120);
        } catch (\Exception $e) {
            $sessionLifetime = 120;
        }

        $lastActivity = session('last_activity', now()->timestamp);
        $inactiveMinutes = (now()->timestamp - $lastActivity) / 60;

        if ($inactiveMinutes >= $sessionLifetime) {

            // --- AGREGAR LOG AQUÍ ---
            \App\Models\Log::create([
                'user_id' => $user->id,
                'idnivel' => 3, // INFORMATIVO (o ADVERTENCIA si prefieres)
                'mensajelogs' => "Sesión cerrada automáticamente por inactividad ({$sessionLifetime} min).",
                'fechalogs' => now(),
            ]);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Su sesión ha expirado por inactividad.'
            ]);
        }

        session(['last_activity' => now()->timestamp]);

        return $next($request);
    }
}
