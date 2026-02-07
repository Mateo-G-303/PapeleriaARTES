<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Livewire\Productos;
use App\Livewire\Proveedores;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\ReporteComprasController;
use App\Http\Controllers\ReporteDashboardController;
use App\Http\Controllers\VentaController;
use App\Livewire\Compras;
use App\Livewire\AuditoriaIndex; // <-- No olvides importar esto arriba
use App\Livewire\ReporteCompras;
use App\Livewire\ReportesIndex;
use App\Livewire\RproductosCategoria;
use App\Livewire\ReporteVentas;
// Página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard principal
use App\Models\Producto;
use App\Models\Categoria;

Route::get('/dashboard', function () {
    // 1. Contar Productos Totales
    $totalProductos = Producto::count();

    // 2. Contar Categorías
    $totalCategorias = Categoria::count();

    // 3. Contar Productos con Stock Bajo (Menos de 10 unidades)
    $bajoStock = Producto::where('stockpro', '<=', 10)->count();

    // 4. Obtener la lista de esos productos urgentes para la tabla
    $listaUrgente = Producto::where('stockpro', '<=', 10)
        ->with('categoria') // Traemos la categoría para mostrarla
        ->take(5) // Solo mostramos los 5 primeros
        ->get();

    return view('dashboard', compact('totalProductos', 'totalCategorias', 'bajoStock', 'listaUrgente'));
})->name('dashboard')
    ->middleware(['auth', 'verified']); // Aseguramos que esté protegido

// Rutas de configuración de usuario
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
        
    Route::get('/auditoria', AuditoriaIndex::class)->name('auditoria');
});

// Rutas protegidas para usuarios normales (con timeout de sesión)
Route::middleware(['auth', 'verified', 'session.timeout'])->group(function () {
    Route::get('/productos', Productos::class)->name('productos');
    Route::get('/proveedores', Proveedores::class)->name('proveedores');
    Route::get('/compras', Compras::class)->name('compras');

    Route::get('/reportes', ReportesIndex::class)
        ->name('reportes.index');

    Route::get('/reportes/compras', ReporteCompras::class)
        ->name('reportes.compras');

    Route::get('/reportes/comprasCategoria',RproductosCategoria::class)
        ->name('reportes.productosCategoria');
     Route::get('/reportes/ventas', ReporteVentas::class)
        ->name('reportes.ventas');

});

// ============================================
// RUTAS DE ADMINISTRACIÓN
// ============================================
Route::middleware(['auth', 'role:Administrador'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestión de Usuarios
    Route::get('usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('usuarios/{id}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    Route::patch('usuarios/{id}/desbloquear', [UserController::class, 'desbloquear'])->name('usuarios.desbloquear');

    // Gestión de Roles
    Route::get('roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RolController::class, 'create'])->name('roles.create');
    Route::post('roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('roles/{id}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{id}', [RolController::class, 'update'])->name('roles.update');
    Route::delete('roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');
    Route::patch('roles/{id}/toggle', [RolController::class, 'toggleStatus'])->name('roles.toggle');

    // Configuraciones de Admin (sesión, bloqueo, IVA)
    Route::get('configuraciones', [ConfiguracionController::class, 'index'])->name('configuraciones.index');
    Route::put('configuraciones', [ConfiguracionController::class, 'update'])->name('configuraciones.update');
    Route::post('configuraciones/iva', [ConfiguracionController::class, 'actualizarIva'])->name('configuraciones.iva');
});

// ============================================
// RUTAS DE VENTAS
// ============================================
Route::middleware(['auth', 'verified', 'session.timeout'])->group(function () {
    // Listado de ventas
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    
    // Crear nueva venta
    Route::get('/ventas/create', [VentaController::class, 'create'])->name('ventas.create');
    
    // Buscar producto por código de barras (AJAX)
    Route::post('/ventas/buscar-producto', [VentaController::class, 'buscarProducto'])->name('ventas.buscar-producto');
    
    // Confirmar venta y obtener resumen (AJAX)
    Route::post('/ventas/confirmar', [VentaController::class, 'confirmarVenta'])->name('ventas.confirmar');
    
    // Guardar venta
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
    
    // Ver detalle de venta
    Route::get('/ventas/{id}', [VentaController::class, 'show'])->name('ventas.show');
    
    // Generar PDF de factura
    Route::get('/ventas/{id}/pdf', [VentaController::class, 'generarPDF'])->name('ventas.pdf');

    Route::get('/ventas/{id}/imprimir', [VentaController::class, 'imprimir'])->name('ventas.imprimir');
});

// Ruta de Categorías
Route::get('/categorias', App\Livewire\Categorias::class)->name('categorias');
