<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Usuario\UsuarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\CanchaController; 
// Importamos los controladores principales
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminReservaController;

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

    // Dashboard (Usamos el DashboardController para cargar la vista principal con datos)
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

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
        
    // ==========================================================
    // Módulo de Gestión Administrativa (CRUD de Reservas y Reportes)
    // ==========================================================
    Route::prefix('gestion')->name('admin.')->group(function () {
        
        // CRUD de Reservas (Index, Edit, Update, Destroy)
        Route::resource('reservas', AdminReservaController::class)->except(['create', 'store', 'show']); 
        
        // **NUEVO:** Ruta para generar informes
        Route::get('reservas/reporte', [AdminReservaController::class, 'reporte'])->name('reservas.reporte');
    });
});