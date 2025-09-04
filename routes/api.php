<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractorController;


Route::prefix('/v1')->group(function () {
    
    // Rutas públicas
    Route::post('/login', [LoginController::class, 'login'])->name('api.login');
    Route::post('/register', [LoginController::class, 'register'])->name('api.register');
    Route::get('/franchises/list', [LoginController::class, 'listFranchisesUser'])->name('api.franchises.list');
    Route::get('/contractor/list', [ContractorController::class,'listForRegister'])->name('api.contractors.list');
    Route::get('/roles/list', [LoginController::class, 'listRoleUser'])->name('api.role.list');
   
    // Rutas protegidas por JWT
    Route::middleware(JwtMiddleware::class)->group(function () {
        
            // CRUD de Prospectos
            Route::post('/prospects/create', [ProspectController::class, 'registerProspect'])->name('api.prospects.create')->middleware('checkrole:1');
            Route::get('/prospects/list', [ProspectController::class, 'listProspects'])->name('api.prospects.index')->middleware('checkrole:1,2');
            Route::get('/prospects/detail/{id}', [ProspectController::class, 'prospectDetails'])->name('api.prospects.show')->middleware('checkrole:1,2');
            Route::put('/prospects/update/{id}', [ProspectController::class, 'updateProspect'])->name('api.prospects.update')->middleware('checkrole:1');
            Route::delete('/prospects/delete/{id}', [ProspectController::class, 'deleteProspect'])->name('api.prospects.delete')->middleware('checkrole:1');

            // CRUD de Usuarios
            Route::post('/users/create', [UserController::class, 'registerUser'])->name('api.users.create')->middleware('checkrole:1');
            Route::get('/users/list', [UserController::class, 'listUser'])->name('api.users.index')->middleware('checkrole:1');
            Route::get('/users/detail/{id}', [UserController::class, 'detailsUser'])->name('api.users.show')->middleware('checkrole:1');
            Route::put('/users/update/{id}', [UserController::class, 'updateUser'])->name('api.users.update')->middleware('checkrole:1,2,3');
            Route::delete('/users/delete/{id}', [UserController::class, 'deleteUser'])->name('api.users.delete')->middleware('checkrole:1,2,3');

            //CRUD Contratistas
            Route::post('/contractors/create', [ContractorController::class, 'registerContractor'])->name('api.contractors.create')->middleware('checkrole:1');
            Route::get('/contractors/list', [ContractorController::class, 'listContractor'])->name('api.contractors.index')->middleware('checkrole:1,2');
            Route::get('/contractors/detail/{id}', [ContractorController::class, 'detailsContractor'])->name('api.contractors.show')->middleware('checkrole:1,2,3');
            Route::put('/contractors/update/{id}', [ContractorController::class, 'updateContractor'])->name('api.contractors.update')->middleware('checkrole:1,2,3');
            Route::delete('/contractors/delete/{id}', [ContractorController::class, 'deleteContractor'])->name('api.contractors.delete')->middleware('checkrole:1');
    });
});
