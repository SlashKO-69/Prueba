<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\SueldoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\Flujo_CajaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ReunionController;
use App\Http\Controllers\BandejaController;
use App\Http\Controllers\AparatoController;

// ─── Raíz → Login ───────────────────────────────────────
Route::get('/', [EmpleadoController::class, 'loginForm'])->name('empleados.loginForm');
Route::post('/login', [EmpleadoController::class, 'login'])->name('empleados.login');
Route::post('/logout', [EmpleadoController::class, 'logout'])->name('empleados.logout');

// ─── CRUD Empleados ──────────────────────────────────────
Route::resource('empleados', EmpleadoController::class);

// ─── CRUD Clientes ───────────────────────────────────────
Route::resource('clientes', ClienteController::class);
Route::get('clientes/exportar/excel', [ClienteController::class, 'exportarExcel'])->name('clientes.exportarExcel');
Route::post('clientes/{ci}/reinscribir', [ClienteController::class, 'reinscribir'])->name('clientes.reinscribir');

// ─── Inscripciones ───────────────────────────────────────
Route::resource('inscripciones', InscripcionController::class)->except(['edit', 'update', 'create']);
Route::get('inscripciones/nueva', [InscripcionController::class, 'create'])->name('inscripciones.create');
Route::get('inscripciones/exportar/excel', [InscripcionController::class, 'exportarExcel'])->name('inscripciones.exportarExcel');

// ─── Promociones ─────────────────────────────────────────
Route::get('promociones', [PromocionController::class, 'index'])->name('promociones.index');
Route::post('promociones', [PromocionController::class, 'store'])->name('promociones.store');
Route::put('promociones/{id}', [PromocionController::class, 'update'])->name('promociones.update');
Route::delete('promociones/{id}', [PromocionController::class, 'destroy'])->name('promociones.destroy');
// ─── Sueldos (solo admin) ────────────────────────────────
Route::get('sueldos', [SueldoController::class, 'index'])->name('sueldos.index');
Route::post('sueldos', [SueldoController::class, 'store'])->name('sueldos.store');
Route::patch('sueldos/{id}/pagar', [SueldoController::class, 'pagar'])->name('sueldos.pagar');
Route::delete('sueldos/{id}', [SueldoController::class, 'destroy'])->name('sueldos.destroy');

// ─── Sesiones ────────────────────────────────────────────
Route::get('sesiones', [SesionController::class, 'index'])->name('sesiones.index');
Route::post('sesiones/entrada', [SesionController::class, 'entrada'])->name('sesiones.entrada');
Route::patch('sesiones/{id}/salida', [SesionController::class, 'salida'])->name('sesiones.salida');
Route::get('sesiones/{id}', [SesionController::class, 'show'])->name('sesiones.show');
Route::post('sesiones/{id}/detalle', [SesionController::class, 'agregarDetalle'])->name('sesiones.detalle');
Route::delete('sesiones/{id}', [SesionController::class, 'destroy'])->name('sesiones.destroy');

// ─── Flujo de Caja (solo admin) ──────────────────────────
Route::get('flujo-caja', [Flujo_CajaController::class, 'index'])->name('flujo-caja.index');

// ─── Horarios (solo admin) ───────────────────────────────
Route::get('horarios', [HorarioController::class, 'index'])->name('horarios.index');
Route::post('horarios', [HorarioController::class, 'store'])->name('horarios.store');
Route::delete('horarios/{id}', [HorarioController::class, 'destroy'])->name('horarios.destroy');

// ─── Reuniones ───────────────────────────────────────────
Route::get('reuniones', [ReunionController::class, 'index'])->name('reuniones.index');
Route::post('reuniones', [ReunionController::class, 'store'])->name('reuniones.store');
Route::get('reuniones/bandeja', [ReunionController::class, 'bandeja'])->name('reuniones.bandeja');
Route::get('reuniones/{id}', [ReunionController::class, 'show'])->name('reuniones.show');
Route::post('reuniones/{id}/asistencia', [ReunionController::class, 'actualizarAsistencia'])->name('reuniones.asistencia');
Route::delete('reuniones/{id}', [ReunionController::class, 'destroy'])->name('reuniones.destroy');

// ─── Bandeja ─────────────────────────────────────────────
Route::get('bandeja', [BandejaController::class, 'index'])->name('bandeja.index');
Route::post('bandeja/reportar', [BandejaController::class, 'reportar'])->name('bandeja.reportar');
Route::patch('bandeja/leido/{id}', [BandejaController::class, 'marcarLeido'])->name('bandeja.leido');

// ─── Aparatos (solo admin) ───────────────────────────────
Route::get('aparatos', [AparatoController::class, 'index'])->name('aparatos.index');
Route::post('aparatos', [AparatoController::class, 'store'])->name('aparatos.store');
Route::patch('aparatos/{id}/estado', [AparatoController::class, 'cambiarEstado'])->name('aparatos.estado');
Route::delete('aparatos/{id}', [AparatoController::class, 'destroy'])->name('aparatos.destroy');