<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cliente extends Model
{
    protected $fillable = [
        'nombre',
        'apaterno',
        'amaterno',
        'ci',
        'fecha_inscripcion',
        'fecha_vencimiento',
    ];
    // Aquí definimos cómo se deben "convertir" automáticamente ciertos atributos
    protected $casts = [
        // Convierte el campo 'fecha_inscripcion' en un objeto Carbon (fecha)
        'fecha_inscripcion' => 'date',

        // Convierte el campo 'fecha_vencimiento' en un objeto Carbon (fecha)
        'fecha_vencimiento' => 'date',];
}
