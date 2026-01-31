<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\User;
use App\Observers\AuditoriaObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Conectas el mismo observador a todos los modelos que quieras
        Producto::observe(AuditoriaObserver::class);
        Categoria::observe(AuditoriaObserver::class);
        User::observe(AuditoriaObserver::class);
    }
}
