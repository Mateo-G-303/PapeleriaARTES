<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use App\Models\User;
use App\Models\Configuracion;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Configurar vistas de Fortify (IMPORTANTE)
        Fortify::loginView(function () {
            return view('livewire.auth.login');
        });

        Fortify::registerView(function () {
            return view('livewire.auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('livewire.auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('livewire.auth.reset-password', ['request' => $request]);
        });

        Fortify::verifyEmailView(function () {
            return view('livewire.auth.verify-email');
        });

        Fortify::confirmPasswordView(function () {
            return view('livewire.auth.confirm-password');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('livewire.auth.two-factor-challenge');
        });

        // Autenticación personalizada con bloqueo
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return null;
            }

           // Verificar si está bloqueado
             if (method_exists($user, 'estaBloqueado') && $user->estaBloqueado()) {
    
             $tiempoTotalEnMinutos = $user->minutosRestantesBloqueo();
    
            $minutos = floor($tiempoTotalEnMinutos);
    
            $segundos = round(($tiempoTotalEnMinutos - $minutos) * 60);

                throw ValidationException::withMessages([   
        'email' => [trans('auth.throttle', [
            'minutes' => $minutos, 
            'seconds' => $segundos
        ])],
    ]);
}

            // Verificar contraseña
            if (!Hash::check($request->password, $user->password)) {
                // Incrementar intentos fallidos si el campo existe
                if (isset($user->intentos_fallidos)) {
                    try {
                        $maxIntentos = (int) Configuracion::obtener('max_login_attempts', 5);
                    } catch (\Exception $e) {
                        $maxIntentos = 5;
                    }
                    
                    $nuevoIntentos = $user->intentos_fallidos + 1;
                    $datos = ['intentos_fallidos' => $nuevoIntentos];

                    if ($nuevoIntentos >= $maxIntentos) {
                        $datos['bloqueado_hasta'] = now()->addMinutes(15);
                    }

                    $user->update($datos);

                    $intentosRestantes = $maxIntentos - $nuevoIntentos;
                    if ($intentosRestantes <= 0) {
                        throw ValidationException::withMessages([
                            'email' => ['Cuenta bloqueada por exceso de intentos fallidos.'],
                        ]);
                    }

                    throw ValidationException::withMessages([
                        'email' => ["Credenciales incorrectas. Intentos restantes: {$intentosRestantes}"],
                    ]);
                }

                return null;
            }

            // Login exitoso - resetear intentos si el campo existe
            if (isset($user->intentos_fallidos)) {
                $user->update([
                    'intentos_fallidos' => 0,
                    'bloqueado_hasta' => null
                ]);
            }

            return $user;
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = mb_strtolower($request->input(Fortify::username())).'|'.$request->ip();
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}