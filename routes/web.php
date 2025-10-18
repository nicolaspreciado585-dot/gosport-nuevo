<?php

use Illuminate\Support\Facades\Route;

// Controladores principales
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Usuario\UsuarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminReservaController;

// Controladores del módulo de ligas
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\StandingsController;

// ==========================================================
// Página pública de bienvenida
// ==========================================================
Route::get('/', function () {
    return view('welcome');
});

// ==========================================================
// Rutas protegidas por autenticación
// ==========================================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // =====================================
    // DASHBOARD
    // =====================================
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // =====================================
    // CRUD USUARIOS
    // =====================================
    Route::resource('usuarios', UsuarioController::class);

    // =====================================
    // MÓDULO DE RESERVAS (Usuario final)
    // =====================================
    Route::get('/reservas/create', [CanchaController::class, 'index'])
        ->name('reservas.create');
    Route::get('/reservas/create/{cancha}', [ReservaController::class, 'create'])
        ->name('reservas.create.cancha');
    Route::post('/reservas', [ReservaController::class, 'store'])
        ->name('reservas.store');
    Route::get('/reservas/confirmacion', function () {
        return view('reservas.confirmacion');
    })->name('reservas.confirmacion');

    // =====================================
    // PERFIL DE USUARIO
    // =====================================
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    // =====================================
    // MÓDULO DE GESTIÓN ADMINISTRATIVA
    // =====================================
    Route::prefix('gestion')->name('admin.')->group(function () {

        Route::resource('reservas', AdminReservaController::class)->except(['create', 'store', 'show']);
        Route::get('reservas/reporte', [AdminReservaController::class, 'reporte'])->name('reservas.reporte');
    });

    // =====================================
    // MÓDULO DE LIGAS (Sistema de competencias)
    // =====================================
    Route::get('/ligas', [LeagueController::class, 'index'])->name('ligas.index');
    Route::get('/ligas/{liga}', [LeagueController::class, 'show'])->name('ligas.show');
    Route::get('/ligas/{liga}/tabla', [StandingsController::class, 'show'])->name('ligas.tabla');
    Route::get('/ligas/{liga}/calendario', [MatchController::class, 'calendar'])->name('ligas.calendario');
    Route::get('/temporadas', [SeasonController::class, 'index'])->name('temporadas.index');
});
