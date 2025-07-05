<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EnfermeraController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\TriajeController;
use App\Http\Controllers\PrediccionController;
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
    Route::get('/settings', [IndexController::class, 'settings'])->name('settings');
    Route::get('/profile', [IndexController::class, 'profile'])->name('profile');

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
    
    /*Rutas de doctores*/
    Route::resource('doctores', DoctorController::class);
    Route::get('/doctores/{iddoctor}', [DoctorController::class, 'show'])->name('doctores.show');
    Route::get('/doctores/create', [DoctorController::class, 'create'])->name('doctores.create');
    Route::post('/doctores', [DoctorController::class, 'store'])->name('doctores.store');
    Route::get('/doctores/{id}/edit', [DoctorController::class, 'edit'])->name('doctores.edit');
    Route::put('/doctores/{idrol}', [DoctorController::class, 'update'])->name('doctores.update');
    Route::delete('/doctores/{id}', [DoctorController::class, 'destroy'])->name('doctores.destroy');

    /*Rutas de enfermeras*/
    Route::get('/enfermeras/create', [EnfermeraController::class, 'create'])->name('enfermeras.create');
    Route::post('/enfermeras', [EnfermeraController::class, 'store'])->name('enfermeras.store');
    Route::get('/enfermeras', [EnfermeraController::class, 'index'])->name('enfermeras.index');
    Route::get('/enfermeras/{idenfermera}', [EnfermeraController::class, 'show'])->name('enfermeras.show');
    Route::get('/enfermeras/{idenfermera}/edit', [EnfermeraController::class, 'edit'])->name('enfermeras.edit');
    Route::put('/enfermeras/{idenfermera}', [EnfermeraController::class, 'update'])->name('enfermeras.update');
    Route::delete('/enfermeras/{idenfermera}', [EnfermeraController::class, 'destroy'])->name('enfermeras.destroy');
    
    /*Rutas de pacientes*/
    Route::resource('pacientes', PacienteController::class);
    Route::get('/pacientes/{idpaciente}', [PacienteController::class, 'show'])->name('pacientes.show');
    Route::get('/pacientes/create', [PacienteController::class, 'create'])->name('pacientes.create');
    Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::get('/pacientes/{id}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
    Route::put('/pacientes/{idpaciente}', [PacienteController::class, 'update'])->name('pacientes.update');
    Route::delete('/pacientes/{idpaciente}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');

    /*Rutas de citas*/
    Route::get('/citas', [CitasController::class, 'index'])->name('citas.index');
    Route::get('/citas/create', [CitasController::class, 'create'])->name('citas.create');
    Route::post('/citas', [CitasController::class, 'store'])->name('citas.store');
    Route::get('/citas/{idcita}/edit', [CitasController::class, 'edit'])->name('citas.edit');
    Route::get('/citas/{idcita}', [CitasController::class, 'show'])->name('citas.show');
    Route::put('/citas/{idcita}', [CitasController::class, 'update'])->name('citas.update');
    Route::delete('/citas/{idcita}', [CitasController::class, 'destroy'])->name('citas.destroy');

    /*Rutas de citas médicas*/
    Route::get('/citas-doctores', [CitasController::class, 'index_doctores'])->name('citas_doctores.index');
    
    /*Rutas de triajes*/
    Route::get('/triajes', [TriajeController::class, 'index'])->name('triajes.index');
    Route::get('/triajes/create', [TriajeController::class, 'create'])->name('triajes.create');
    Route::post('/triajes', [TriajeController::class, 'store'])->name('triajes.store');
    Route::get('/triajes/{idtriaje}/edit', [TriajeController::class, 'edit'])->name('triajes.edit');
    Route::get('/triajes/{idtriaje}', [TriajeController::class, 'show'])->name('triajes.show');
    Route::put('/triajes/{idtriaje}', [TriajeController::class, 'update'])->name('triajes.update');
    Route::delete('/triajes/{idtriaje}', [TriajeController::class, 'destroy'])->name('triajes.destroy');

    /*Rutas de predicciones*/
    Route::get('/predicciones', [PrediccionController::class, 'index'])->name('predicciones.index');
    Route::get('/predicciones/create/{idcita?}', [PrediccionController::class, 'create'])->name('predicciones.create');
    Route::post('/predicciones', [PrediccionController::class, 'store'])->name('predicciones.store');
    Route::get('/predicciones/{idprediccion}', [PrediccionController::class, 'show'])->name('predicciones.show');
    Route::get('/predicciones/{idprediccion}/edit', [PrediccionController::class, 'edit'])->name('predicciones.edit');
    Route::put('/predicciones/{idprediccion}', [PrediccionController::class, 'update'])->name('predicciones.update');
    Route::delete('/predicciones/{idprediccion}', [PrediccionController::class, 'destroy'])->name('predicciones.destroy');
});
