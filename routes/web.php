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
use App\Http\Controllers\VentaController; 
use App\Http\Controllers\ExportController;
use App\Livewire\Compras;
use App\Livewire\AuditoriaIndex;
use App\Livewire\ReporteCompras;
use App\Livewire\ReportesIndex;
use App\Livewire\RproductosCategoria;
use App\Livewire\ReporteVentas; // <--- Agregado según tu código
use App\Livewire\LogIndex; 
use App\Livewire\ReportesGraficos;
use App\Models\Producto;
use App\Models\Categoria;

// ============================================
// 1. PÁGINAS PÚBLICAS Y DASHBOARD
// ============================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $totalProductos = Producto::count();
    $totalCategorias = Categoria::count();
    $bajoStock = Producto::where('stockpro', '<=', 10)->count();
    $listaUrgente = Producto::where('stockpro', '<=', 10)
        ->with('categoria')
        ->take(5)
        ->get();

    return view('dashboard', compact('totalProductos', 'totalCategorias', 'bajoStock', 'listaUrgente'));
})->name('dashboard')
  ->middleware(['auth', 'verified']);

// ============================================
// 2. CONFIGURACIÓN DE PERFIL (Para todos)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');
    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(when(Features::canManageTwoFactorAuthentication() && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'), ['password.confirm'], []))
        ->name('two-factor.show');
});

// ============================================
// 3. ZONA DE INFORMACIÓN Y CONSULTA
// PERMISOS: Admin, Vendedor y Auditor
// (El auditor necesita ver esto para cruzar datos, el vendedor para consultar)
// ============================================
Route::middleware(['auth', 'verified', 'session.timeout', 'role:Administrador|Vendedor|Auditor'])->group(function () {
    
    // Inventario y Proveedores
    Route::get('/productos', Productos::class)->name('productos');
    Route::get('/categorias', App\Livewire\Categorias::class)->name('categorias');
    Route::get('/proveedores', Proveedores::class)->name('proveedores');
    
    // Compras
    Route::get('/compras', Compras::class)->name('compras');

    // Reportes
    Route::get('/reportes', ReportesIndex::class)->name('reportes.index');
    Route::get('/reportes/compras', ReporteCompras::class)->name('reportes.compras');
    Route::get('/reportes/comprasCategoria', RproductosCategoria::class)->name('reportes.productosCategoria');
    Route::get('/reportes/ventas', ReporteVentas::class)->name('reportes.ventas');

    // HISTORIAL DE VENTAS (Solo ver, no crear)
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    // Importante: where id es numero para que no choque con 'create'
    Route::get('/ventas/{id}', [VentaController::class, 'show'])->name('ventas.show')->where('id', '[0-9]+'); 
    Route::get('/ventas/{id}/pdf', [VentaController::class, 'generarPDF'])->name('ventas.pdf');
    Route::get('/ventas/{id}/imprimir', [VentaController::class, 'imprimir'])->name('ventas.imprimir');
});

// ============================================
// 4. ZONA OPERATIVA DE CAJA (VENTAS ACTIVAS)
// PERMISOS: Solo Admin y Vendedor
// (EL AUDITOR TIENE PROHIBIDO ENTRAR AQUÍ)
// ============================================
Route::middleware(['auth', 'session.timeout', 'role:Administrador|Vendedor'])->group(function () {
    Route::get('/ventas/create', [VentaController::class, 'create'])->name('ventas.create');
    Route::post('/ventas/buscar-producto', [VentaController::class, 'buscarProducto'])->name('ventas.buscar-producto');
    Route::post('/ventas/confirmar', [VentaController::class, 'confirmarVenta'])->name('ventas.confirmar');
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
});

// ============================================
// 5. ZONA DE AUDITORÍA Y SEGURIDAD
// PERMISOS: Admin y Auditor
// (El Vendedor NO entra aquí)
// ============================================
Route::middleware(['auth', 'role:Administrador|Auditor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/auditoria', AuditoriaIndex::class)->name('auditoria');
    Route::get('/logs', LogIndex::class)->name('logs');
    Route::get('/reportes/seguridad', ReportesGraficos::class)->name('reportes.seguridad');
    
    // Exportar Excel/CSV
    Route::get('/exportar/logs', [ExportController::class, 'exportarLogs'])->name('exportar.logs');
    Route::get('/exportar/auditoria', [ExportController::class, 'exportarAuditoria'])->name('exportar.auditoria');
});

// ============================================
// 6. ADMINISTRACIÓN TOTAL (Gestión del Sistema)
// PERMISOS: Solo Administrador
// ============================================
Route::middleware(['auth', 'role:Administrador'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');

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

    // Configuraciones Sensibles
    Route::get('configuraciones', [ConfiguracionController::class, 'index'])->name('configuraciones.index');
    Route::put('configuraciones', [ConfiguracionController::class, 'update'])->name('configuraciones.update');
    Route::post('configuraciones/iva', [ConfiguracionController::class, 'actualizarIva'])->name('configuraciones.iva');
    Route::get('configuraciones/backup', [ConfiguracionController::class, 'backupDatabase'])->name('configuraciones.backup');
    Route::get('configuraciones/exportar-datos', [ConfiguracionController::class, 'exportarDatosNegocio'])->name('configuraciones.exportar-datos');
});