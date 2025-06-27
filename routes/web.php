<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\DB;

Route::get('/', [IndexController::class, 'index'])->name('index');
