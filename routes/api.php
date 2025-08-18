<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\UserController;

Route::post('/login', [LoginController::class, 'login'])
    ->name('login');

Route::post('/register', [LoginController::class, 'register'])
    ->name('register');

Route::post('/prospect/register', [ProspectController::class, 'registerProspect'])
->name('prospect.register');

Route::get('prospect/list', [ProspectController::class, 'listProspects'])
->name('prospect.list');

Route::get('prospect\details', [ProspectController::class, 'prospectDetails'])
->name('prospect.details');

Route::put('prospect\update', [ProspectController::class, 'updateProspect'])
->name('prospect.update');

Route::delete('prospect\delete', [ProspectController::class, 'deleteProspect'])
->name('prospect.delete');