<?php

use App\Http\Controllers\Login;
use Illuminate\Support\Facades\Route;


Route::get('/login', [Login::class, 'getLogin'])
    ->name('login');
