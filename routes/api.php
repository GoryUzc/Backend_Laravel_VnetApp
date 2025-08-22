<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractorController;


Route::prefix('/api')->group(function () {
    // Rutas públicas
    Route::post('/login', [LoginController::class, 'login'])->name('api.login');
    Route::post('/register', [LoginController::class, 'register'])->name('api.register');

    // Rutas protegidas por JWT
    Route::middleware('jwt.auth')->group(function () {
        // Rutas para Admin (role_id = 1)
        Route::middleware('check.role:1')->group(function () {
            // CRUD de Prospectos
            Route::post('/prospects', [ProspectController::class, 'registerProspect'])->name('api.prospects.create');
            Route::get('/prospects', [ProspectController::class, 'listProspects'])->name('api.prospects.index');
            Route::get('/prospects/{id}', [ProspectController::class, 'prospectDetails'])->name('api.prospects.show');
            Route::put('/prospects/{id}', [ProspectController::class, 'updateProspect'])->name('api.prospects.update');
            Route::delete('/prospects/{id}', [ProspectController::class, 'deleteProspect'])->name('api.prospects.delete');

            // CRUD de Usuarios
            Route::post('/users', [UserController::class, 'registerUser'])->name('api.users.create');
            Route::get('/users', [UserController::class, 'listUser'])->name('api.users.index');
            Route::get('/users/{id}', [UserController::class, 'detailsUser'])->name('api.users.show');
            Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('api.users.update');
            Route::delete('/users/{id}', [UserController::class, 'deleteUser'])->name('api.users.delete');
        });

        // Rutas para Supervisor (role_id = 2)
        Route::middleware('check.role:2')->group(function () {
            Route::get('/prospects', [ProspectController::class, 'listProspects'])->name('api.supervisor.prospects.index');
            Route::get('/prospects/{id}', [ProspectController::class, 'prospectDetails'])->name('api.supervisor.prospects.show');
        });

        // Rutas para Contractor (role_id = 3)
        Route::middleware('check.role:3')->group(function () {
            // Aquí puedes añadir rutas específicas para contractors
        });
    });
});