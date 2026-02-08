<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Log; // <--- ¡IMPORTANTE! Tienes que agregar esto

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        // 1. Capturar usuario ANTES del logout
        $user = Auth::guard('web')->user();

        if ($user) {
            // Quitamos el try/catch temporalmente para ver si salta un error en pantalla
            Log::create([
                'user_id' => $user->id,
                'idnivel' => 3, 
                'mensajelogs' => 'Cierre de sesión voluntario. IP: ' . request()->ip(),
                'fechalogs' => now(),
            ]);
        }

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }
}