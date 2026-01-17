<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', fn () => view('home'))->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categorias', CategoriaController::class);
    Route::resource('productos', ProductoController::class);
    Route::get('ventas/cierre', [VentaController::class, 'cierre'])->name('ventas.cierre');
    Route::post('ventas/cierre', [VentaController::class, 'guardarCierre'])->name('ventas.cierre.guardar');
    Route::get('ventas/cierres', [VentaController::class, 'cierresIndex'])->name('ventas.cierres.index');
    Route::get('ventas/cierres/{cierreCaja}', [VentaController::class, 'cierresShow'])->name('ventas.cierres.show');
    Route::resource('ventas', VentaController::class);
    Route::post('proveedores/{proveedor}/acciones', [ProveedorController::class, 'storeAccion'])
        ->name('proveedores.acciones.store');
    Route::resource('proveedores', ProveedorController::class)
        ->parameters(['proveedores' => 'proveedor']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return 'Panel de administrador';
    });

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::patch('/usuarios/{user}/role', [UsuarioController::class, 'updateRole'])->name('usuarios.role');
    Route::delete('/usuarios/{user}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});

require __DIR__ . '/auth.php';
