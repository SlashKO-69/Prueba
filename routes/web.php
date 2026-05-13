<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\SueldoController;

// ─── Raíz → Login ───────────────────────────────────────
Route::get('/', [EmpleadoController::class, 'loginForm'])->name('empleados.loginForm');
Route::post('/login', [EmpleadoController::class, 'login'])->name('empleados.login');
Route::post('/logout', [EmpleadoController::class, 'logout'])->name('empleados.logout');

// ─── CRUD Empleados ──────────────────────────────────────
Route::resource('empleados', EmpleadoController::class);

// ─── CRUD Clientes ───────────────────────────────────────
Route::resource('clientes', ClienteController::class);
Route::post('clientes/{ci}/reinscribir', [ClienteController::class, 'reinscribir'])->name('clientes.reinscribir');

// ─── Inscripciones ───────────────────────────────────────
Route::resource('inscripciones', InscripcionController::class)->except(['edit', 'update', 'create']);
Route::get('inscripciones/nueva', [InscripcionController::class, 'create'])->name('inscripciones.create');

// ─── Sueldos (solo admin) ────────────────────────────────
Route::get('sueldos', [SueldoController::class, 'index'])->name('sueldos.index');
Route::post('sueldos', [SueldoController::class, 'store'])->name('sueldos.store');
Route::patch('sueldos/{id}/pagar', [SueldoController::class, 'pagar'])->name('sueldos.pagar');
Route::delete('sueldos/{id}', [SueldoController::class, 'destroy'])->name('sueldos.destroy');