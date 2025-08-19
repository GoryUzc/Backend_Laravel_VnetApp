<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRole;


/**
 * Rutas de atenticacion y registro de usuarios
 */
Route::post('/login', [LoginController::class, 'login'])
    ->name('login');

Route::post('/register', [LoginController::class, 'register'])
    ->name('register');


/**
 * RUTAS ADMIN
 */
Route::middleware(['auth.jwt', 'check.role:admin'])->group(function () {
   
    // Registro de prospectos
    Route::post('/prospect/register', [ProspectController::class, 'registerProspect'])
        ->name('prospect.register');
   
//lista de prospectos 
    Route::get('prospect/list', [ProspectController::class, 'listProspects'])
        ->name('prospect.list');
   
// Detalles de prospecto
Route::get('prospect\details', [ProspectController::class, 'prospectDetails'])
        ->name('prospect.details');

// Actualizar prospecto
Route::put('prospect\update', [ProspectController::class, 'updateProspect'])
        ->name('prospect.update');

// Eliminar prospecto
Route::delete('prospect\delete', [ProspectController::class, 'deleteProspect'])
        ->name('prospect.delete');
});
   

/**
 * RUTAS SUPERVISOR 
 */
Route::middleware(['auth', 'check.role:supervisor'])->group(function () {
    
    //lista de prospectos 
    Route::get('prospect/list', [ProspectController::class, 'listProspects'])
        ->name('prospect.list');

    // Detalles de prospecto
    Route::get('prospect\details', [ProspectController::class, 'prospectDetails'])
        ->name('prospect.details');

});


/**
 * RUTAS CONTRATISTA
 */
Route::middleware(['auth', 'check.role:contractor'])->group(function () {
    
});


/**
 * RUTAS PROSPECTOS 
 */
Route::middleware(['auth.jwt', 'check.role:prospect'])->group(function () {
 //Rutas
});