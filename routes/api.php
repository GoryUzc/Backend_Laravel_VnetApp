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
    Route::get('/franchises/list', [LoginController::class, 'listFranchisesUser'])->name('api.franchises.list');
    Route::get('/contractors/list', [ContractorController::class,'listForRegistration'])->name('api.contractors.list');
    Route::get('/role/list', [LoginController::class, 'listRoleUser'])->name('api.role.list');
   
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

            //CRUD Contratistas
            Route::post('/contractor', [ContractorController::class, 'registerContractor'])->name('api.contractors.create');
            Route::get('/contractor', [ContractorController::class, 'listContractor'])->name('api.contractors.index');
            Route::get('/contractor/{id}', [ContractorController::class, 'detailsContractor'])->name('api.contractors.show');
            Route::put('/contractor/{id}', [ContractorController::class, 'updateContractor'])->name('api.contractors.update');
            Route::delete('/contractor/{id}', [ContractorController::class, 'deleteContractor'])->name('api.contractors.delete');
        });

        // Rutas para Supervisor (role_id = 2)
        Route::middleware('check.role:2')->group(function () {
            
            //Prospectos listar y consulta
            Route::get('/prospects', [ProspectController::class, 'listProspects'])->name('api.supervisor.prospects.index');
            Route::get('/prospects/{id}', [ProspectController::class, 'prospectDetails'])->name('api.supervisor.prospects.show');

            //Contratistas:
            //Crear, listar 
            //Consulta solo por sucursal
            //Actualizar solo por sucursal
            Route::post('/contractor', [ContractorController::class, 'registerContractor'])->name('api.contractors.create');
            Route::get('/contractor', [ContractorController::class, 'listContractor'])->name('api.contractors.index');
            Route::get('/contractor/{id}', [ContractorController::class, 'detailsContractor'])->name('api.contractors.show');
            Route::put('/contractor/{id}', [ContractorController::class, 'updateContractor'])->name('api.contractors.update');

        });

        // Rutas para Contractor (role_id = 3)
        Route::middleware('check.role:3')->group(function () {
            //Contratista Consulta y actualizacion a su empresa
            Route::post('/contractor', [ContractorController::class, 'registerContractor'])->name('api.contractors.create');
            Route::get('/contractor/{id}', [ContractorController::class, 'detailsContractor'])->name('api.contractors.show');
            Route::put('/contractor/{id}', [ContractorController::class, 'updateContractor'])->name('api.contractors.update');
        });
    });
});