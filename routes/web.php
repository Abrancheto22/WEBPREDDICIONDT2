<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolController;
use Illuminate\Support\Facades\DB;

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta protegida
Route::middleware(['auth'])->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::get('/dashboard', [IndexController::class, 'dashboard'])->name('dashboard');
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/users', [IndexController::class, 'users'])->name('users.index');
});
