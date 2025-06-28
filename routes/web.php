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

    /*Rutas de roles*/
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{idrol}', [RolController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');

    /* Rutas de usuarios */
    Route::get('/users', [IndexController::class, 'users'])->name('users.index');
    Route::get('/users/create', [IndexController::class, 'createUser'])->name('users.create');
    Route::post('/users', [IndexController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [IndexController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{idrol}', [IndexController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [IndexController::class, 'deleteUser'])->name('users.destroy');
    
});
