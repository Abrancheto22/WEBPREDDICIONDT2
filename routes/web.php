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
use App\Exports\PrediccionesExport;
use Maatwebsite\Excel\Facades\Excel;

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta protegida
Route::middleware(['auth'])->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::get('/dashboard', [IndexController::class, 'dashboard'])->name('dashboard')->middleware('role:1,2');
    Route::get('/settings', [IndexController::class, 'settings'])->name('settings');
    Route::get('/profile', [IndexController::class, 'profile'])->name('profile');

    /*Rutas de roles*/
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index')->middleware('role:1');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create')->middleware('role:1');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store')->middleware('role:1');
    Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('roles.edit')->middleware('role:1');
    Route::put('/roles/{idrol}', [RolController::class, 'update'])->name('roles.update')->middleware('role:1');
    Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy')->middleware('role:1');

    /* Rutas de usuarios */
    Route::get('/users', [IndexController::class, 'users'])->name('users.index')->middleware('role:1');
    Route::get('/users/create', [IndexController::class, 'createUser'])->name('users.create')->middleware('role:1');
    Route::post('/users', [IndexController::class, 'storeUser'])->name('users.store')->middleware('role:1');
    Route::get('/users/{id}/edit', [IndexController::class, 'editUser'])->name('users.edit')->middleware('role:1');
    Route::put('/users/{idrol}', [IndexController::class, 'updateUser'])->name('users.update')->middleware('role:1');
    Route::delete('/users/{id}', [IndexController::class, 'deleteUser'])->name('users.destroy')->middleware('role:1');
    
    /*Rutas de doctores*/
    Route::get('/doctores/costos', [DoctorController::class, 'costos'])->name('doctores.costos')->middleware('role:1');
    Route::get('/doctores/costos/export', [DoctorController::class, 'exportCostos'])->name('doctores.costos.export')->middleware('role:1');
    Route::get('/doctores/costos/confusion/export', [DoctorController::class, 'exportConfusion'])->name('doctores.costos.confusion.export')->middleware('role:1');
    
    // Rutas específicas ANTES del resource
    Route::get('/doctores/create', [DoctorController::class, 'create'])->name('doctores.create')->middleware('role:1,2');
    Route::post('/doctores', [DoctorController::class, 'store'])->name('doctores.store')->middleware('role:1,2');
    Route::get('/doctores/{id}/edit', [DoctorController::class, 'edit'])->name('doctores.edit')->middleware('role:1,2');
    Route::put('/doctores/{idrol}', [DoctorController::class, 'update'])->name('doctores.update')->middleware('role:1,2');
    Route::delete('/doctores/{id}', [DoctorController::class, 'destroy'])->name('doctores.destroy')->middleware('role:1');
    
    // Resource route (maneja automáticamente show, index, etc.)
    // NOTA: Como ya definiste create, store, edit, update, destroy manualmente arriba,
    // el resource podría duplicar rutas si no tienes cuidado.
    // La mejor práctica es usar `except` para las que ya definiste o dejar solo resource.
    // Dado que tienes rutas personalizadas con parámetros específicos (idrol, id),
    // mantendremos las manuales y dejaremos el resource SOLO para index y show genérico si hace falta,
    // pero el error es que resource crea 'doctores.show' y tú tenías otra ruta manual 'doctores.show'.
    
    Route::resource('doctores', DoctorController::class)->except(['create', 'store', 'edit', 'update', 'destroy'])->middleware('role:1,2');
    
    // La ruta manual que causaba conflicto:
    // Route::get('/doctores/{iddoctor}', [DoctorController::class, 'show'])->name('doctores.show');
    // Ya no es necesaria porque resource la genera, o si quieres usar la manual, no uses resource.

    /*Rutas de enfermeras*/
    Route::get('/enfermeras/create', [EnfermeraController::class, 'create'])->name('enfermeras.create')->middleware('role:1,3');
    Route::post('/enfermeras', [EnfermeraController::class, 'store'])->name('enfermeras.store')->middleware('role:1,3');
    Route::get('/enfermeras', [EnfermeraController::class, 'index'])->name('enfermeras.index')->middleware('role:1');
    Route::get('/enfermeras/{idenfermera}', [EnfermeraController::class, 'show'])->name('enfermeras.show')->middleware('role:1,3');
    Route::get('/enfermeras/{idenfermera}/edit', [EnfermeraController::class, 'edit'])->name('enfermeras.edit')->middleware('role:1,3');
    Route::put('/enfermeras/{idenfermera}', [EnfermeraController::class, 'update'])->name('enfermeras.update')->middleware('role:1,3');
    Route::delete('/enfermeras/{idenfermera}', [EnfermeraController::class, 'destroy'])->name('enfermeras.destroy')->middleware('role:1');
    
    /*Rutas de pacientes*/
    // Definir rutas fijas ANTES del resource para evitar colisiones con /pacientes/{id}
    Route::get('/pacientes/panel', [PacienteController::class, 'panel'])->name('pacientes.panel')->middleware('role:1,3,4');
    
    // Rutas manuales específicas
    Route::get('/pacientes/create', [PacienteController::class, 'create'])->name('pacientes.create')->middleware('role:1,3');
    Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store')->middleware('role:1,3');
    Route::get('/pacientes/{id}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit')->middleware('role:1,3,4');
    Route::put('/pacientes/{idpaciente}', [PacienteController::class, 'update'])->name('pacientes.update')->middleware('role:1,3,4');
    Route::delete('/pacientes/{idpaciente}', [PacienteController::class, 'destroy'])->name('pacientes.destroy')->middleware('role:1');
    
    // Resource route con excepciones para evitar duplicados
    Route::resource('pacientes', PacienteController::class)->except(['create', 'store', 'edit', 'update', 'destroy'])->middleware('role:1,3,4');
    
    // Ruta manual que causaba conflicto (ya manejada por resource o eliminada si es redundante)
    // Route::get('/pacientes/{idpaciente}', [PacienteController::class, 'show'])->name('pacientes.show');

    /*Rutas de citas*/
    Route::get('/citas', [CitasController::class, 'index'])->name('citas.index')->middleware('role:1,2,3');
    Route::get('/citas/create', [CitasController::class, 'create'])->name('citas.create')->middleware('role:1,3');
    Route::post('/citas', [CitasController::class, 'store'])->name('citas.store')->middleware('role:1,3');
    Route::get('/citas/{idcita}/edit', [CitasController::class, 'edit'])->name('citas.edit')->middleware('role:1,3');
    Route::get('/citas/{idcita}', [CitasController::class, 'show'])->name('citas.show')->middleware('role:1,2,3');
    Route::put('/citas/{idcita}', [CitasController::class, 'update'])->name('citas.update')->middleware('role:1,3');
    Route::delete('/citas/{idcita}', [CitasController::class, 'destroy'])->name('citas.destroy')->middleware('role:1,3');

    /*Rutas de citas médicas*/
    Route::get('/citas-doctores', [CitasController::class, 'index_doctores'])->name('citas_doctores.index')->middleware('role:1,2,3');
    
    /*Rutas de triajes*/
    Route::get('/triajes', [TriajeController::class, 'index'])->name('triajes.index')->middleware('role:1,3');
    Route::get('/triajes/create', [TriajeController::class, 'create'])->name('triajes.create')->middleware('role:1,3');
    Route::post('/triajes', [TriajeController::class, 'store'])->name('triajes.store')->middleware('role:1,3');
    Route::get('/triajes/{idtriaje}/edit', [TriajeController::class, 'edit'])->name('triajes.edit')->middleware('role:1,3');
    Route::get('/triajes/{idtriaje}', [TriajeController::class, 'show'])->name('triajes.show')->middleware('role:1,3');
    Route::put('/triajes/{idtriaje}', [TriajeController::class, 'update'])->name('triajes.update')->middleware('role:1,3');
    Route::delete('/triajes/{idtriaje}', [TriajeController::class, 'destroy'])->name('triajes.destroy')->middleware('role:1,3');

    /*Rutas de predicciones*/
    Route::get('/predicciones/exportar', function() {
        return Excel::download(new PrediccionesExport, 'predicciones.xlsx');
    })->name('predicciones.exportar')->middleware('role:1');
    
    Route::get('/predicciones', [PrediccionController::class, 'index'])->name('predicciones.index');
    Route::get('/predicciones/create/{idcita?}', [PrediccionController::class, 'create'])->name('predicciones.create')->middleware('role:1,2');
    Route::post('/predicciones', [PrediccionController::class, 'store'])->name('predicciones.store')->middleware('role:1,2');
    Route::get('/predicciones/{idprediccion}', [PrediccionController::class, 'show'])->name('predicciones.show');
    Route::get('/predicciones/{idprediccion}/edit', [PrediccionController::class, 'edit'])->name('predicciones.edit')->middleware('role:1,2');
    Route::post('/predicciones/{idprediccion}/process-edit', [PrediccionController::class, 'processEditedPrediction'])->name('predicciones.process_edited_prediction')->middleware('role:1,2');
    Route::put('/predicciones/{idprediccion}', [PrediccionController::class, 'updateConfirmedPrediction'])->name('predicciones.update_confirmed_prediction')->middleware('role:1,2');
    Route::delete('/predicciones/{idprediccion}', [PrediccionController::class, 'destroy'])->name('predicciones.destroy')->middleware('role:1,2');
    Route::post('/predicciones/guardar-confirmada', [PrediccionController::class, 'saveConfirmedPrediction'])->name('predicciones.save_confirmed_prediction')->middleware('role:1,2');
    Route::post('/predicciones/analizar-gemini', [PrediccionController::class, 'analyzeWithGemini'])->name('predicciones.analyze_gemini')->middleware('role:1,2');
    Route::get('/predicciones/pdf/{id}', [PrediccionController::class, 'pdf'])->name('predicciones.pdf');
    Route::post('/predicciones/send-email/{id}', [PrediccionController::class, 'sendEmail'])->name('predicciones.sendEmail')->middleware('role:1,2');
    Route::get('/predicciones/{id}/attachment/{index}', [PrediccionController::class, 'downloadAttachment'])->name('predicciones.downloadAttachment');
    Route::post('/predicciones/{idprediccion}/validar', [PrediccionController::class, 'updateValidacion'])->name('predicciones.update_validacion')->middleware('role:1,2');
   
});
