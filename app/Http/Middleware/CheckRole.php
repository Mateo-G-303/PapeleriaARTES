<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->rol->nombrerol ?? null;

        if ($userRole !== $role) {
            // --- REGISTRAMOS LA ANOMALÍA POR ROL ---
            \App\Models\Log::create([
                'user_id' => $user->id,
                'idnivel' => 1, // CRÍTICO
                'mensajelogs' => "Fallo de Rol: Usuario con rol [$userRole] intentó acceder a zona reservada para [$role]. Ruta: " . $request->path(),
                'fechalogs' => now(),
            ]);

            abort(403, 'No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
