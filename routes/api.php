<?php

use App\Http\Controllers\Login;
use Illuminate\Support\Facades\Route;


Route::post('/login', [Login::class, 'login'])
    ->name('login');

Route::post('/register', [Login::class, 'register'])
    ->name('register');
