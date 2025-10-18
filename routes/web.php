<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Usuario\UsuarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminReservaController;
use App\Http\Controllers\EventoController;

// Página pública de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Rutas protegidas por autenticación
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // CRUD Usuarios
    Route::resource('usuarios', UsuarioController::class);

    // ==========================================================
    // Módulo de reservas (Rutas para el usuario final)
    // ==========================================================
    Route::get('/reservas/create', [CanchaController::class, 'index']) 
        ->name('reservas.create'); 
    Route::get('/reservas/create/{cancha}', [ReservaController::class, 'create'])
        ->name('reservas.create.cancha');
    Route::post('/reservas', [ReservaController::class, 'store'])
        ->name('reservas.store'); 
    Route::get('/reservas/confirmacion', function() {
        return view('reservas.confirmacion');
    })->name('reservas.confirmacion');

    // ==========================================================
    // Módulo de Gestión Administrativa (CRUD de Eventos y Reservas)
    // Las rutas generadas aquí son 'admin.eventos.index' y 'admin.reservas.index'
    // ==========================================================
    Route::prefix('gestion')->name('admin.')->group(function () {
        
        // CRUD de Eventos
        Route::resource('eventos', EventoController::class);
        Route::get('eventos/reporte', [EventoController::class, 'reporte'])->name('eventos.reporte');
        
        // CRUD de Reservas (Index, Edit, Update, Destroy)
        Route::resource('reservas', AdminReservaController::class)->except(['create', 'store', 'show']); 
        Route::get('reservas/reporte', [AdminReservaController::class, 'reporte'])->name('reservas.reporte');
    });
});