<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\User;
use App\Observers\AuditoriaObserver;
use App\Models\Compra;
use App\Models\Configuracion;
use App\Models\DetalleCompra;
use App\Models\DetalleVenta;
use App\Models\Venta;
use App\Models\Permiso;
use App\Models\Proveedor;
use App\Models\Rol;





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
        Compra::observe(AuditoriaObserver::class);
        Configuracion::observe(AuditoriaObserver::class);
        DetalleCompra::observe(AuditoriaObserver::class);
        DetalleVenta::observe(AuditoriaObserver::class);
        Venta::observe(AuditoriaObserver::class);
        Permiso::observe(AuditoriaObserver::class);
        Proveedor::observe(AuditoriaObserver::class);
        Rol::observe(AuditoriaObserver::class);
    }
}
