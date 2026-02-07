<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Log;

class CheckPermiso
{
    public function handle(Request $request, Closure $next, string $permiso): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar si el usuario tiene el permiso
        if (!$user->tienePermiso($permiso)) {
            // --- AQUÍ REGISTRAMOS LA ANOMALÍA ANTES DE BLOQUEAR ---
            \App\Models\Log::create([
                'user_id' => $user->id,
                'idnivel' => 1, // 1 = CRÍTICO
                'mensajelogs' => "Intento de acceso no autorizado al permiso: [$permiso] en la ruta: " . $request->path(),
                'fechalogs' => now(),
            ]);

            abort(403, 'No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
