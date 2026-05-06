<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class personal extends Model
{
    protected $fillable = [
        'nombre',
        'apaterno',
        'amaterno',
        'ci',
        'cargo',              // Ej: entrenador, recepcionista, etc.
        'fecha_contratacion', // Fecha en que empezó a trabajar
        'fecha_pago',         // Última fecha de pago
    ];

    // 👇 Convertimos automáticamente las fechas en objetos Carbon
    protected $casts = [
        'fecha_contratacion' => 'date',
        'fecha_pago' => 'date',
    ];
}
