<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PersonalController;
Route::get('/', function () {
    return redirect()->route('login');
});

// Pantalla de login de operadores
Route::get('/login', [OperadorController::class, 'login'])->name('login');

// Procesa el acceso según el rol (administradora o recepcionista)
Route::post('/authenticate', [OperadorController::class, 'authenticate'])->name('authenticate');

// Dashboard para administradora (clientes + personal)
Route::get('/dashboard/admin', [OperadorController::class, 'dashboardAdmin'])->name('dashboard.admin');

// Dashboard para recepcionista (solo clientes)
Route::get('/dashboard/recepcionista', [OperadorController::class, 'dashboardRecepcionista'])->name('dashboard.recepcionista');

// CRUD de clientes
Route::resource('clientes', ClienteController::class);

// CRUD de personal
Route::resource('personals', PersonalController::class);
