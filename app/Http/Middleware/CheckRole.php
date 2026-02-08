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

        // 1. Separamos los roles permitidos (ej: "Administrador|Vendedor" se vuelve un arreglo)
        $rolesPermitidos = explode('|', $role);

        // 2. Verificamos si el rol del usuario NO está dentro de los permitidos
        if (!in_array($userRole, $rolesPermitidos)) {

            // --- REGISTRAMOS LA ANOMALÍA POR ROL ---
            \App\Models\Log::create([
                'user_id' => $user->id,
                'idnivel' => 1, // CRÍTICO
                'mensajelogs' => "Fallo de Rol: Usuario con rol [" . ($userRole ?? 'Ninguno') . "] intentó acceder a zona reservada para [" . implode(', ', $rolesPermitidos) . "]. Ruta: " . $request->path(),
                'fechalogs' => now(),
            ]);

            abort(403, 'No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
