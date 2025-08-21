<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\UserController;


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
//      RUTAS CRUD PROSPECTOS

    // Registro de prospectos
    Route::post('/prospect/register', [ProspectController::class, 'registerProspect'])->name('prospect.register');
   //lista de prospectos 
    Route::get('/prospect/list', [ProspectController::class, 'listProspects'])->name('prospect.list');
    // Detalles de prospecto
    Route::get('/prospect/details/{id}', [ProspectController::class, 'prospectDetails'])->name('prospect.details');
        // Actualizar prospecto
    Route::put('/prospect/update/{id}', [ProspectController::class, 'updateProspect'])->name( 'prospect.update');
        // Eliminar prospecto
    Route::delete('/prospect/delete/{id}', [ProspectController::class, 'deleteProspect'])->name('prospect.delete');

// RUTAS CRUD USUARIOS
    //Registro de prospectos
     Route::post('/user/register', [UserController::class, 'registerUser'])->name('user.register');
     // Lista de Usuarios
     Route::get('/user/list', [UserController::class, 'listUser'])->name('user.list');
    // Detalle de un usuario
     Route::get('/user/details/{id}', [UserController::class, 'detailsUser'])->name('user.details');
    // Actualizacion de Usuario
     Route::put('/user/update/{id}', [UserController::class,'updateUser'])->name('user.update');
    // Eliminar Usuario 
    Route::delete('/user/delete/{id}', [UserController::class,'deleteUser'])->name('user.delete');


    
});
   

/**
 * RUTAS SUPERVISOR 
 */
Route::middleware(['auth.jwt', 'check.role:supervisor'])->group(function () {
    
    //lista de prospectos 
    Route::get('/prospect/list', [ProspectController::class, 'listProspects'])->name('/prospect.list');

    // Detalles de prospecto
    Route::get('/prospect\details/{id}', [ProspectController::class, 'prospectDetails'])->name('/prospect.details');

});


/**
 * RUTAS CONTRATISTA
 */
Route::middleware(['auth.jwt', 'check.role:contractor'])->group(function () {
    
});