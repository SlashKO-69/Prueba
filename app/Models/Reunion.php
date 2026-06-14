<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    protected $table = 'reunions';
    protected $primaryKey = 'id_reunion';
    public $incrementing = true;

    protected $fillable = [
        'fecha_reunion',
        'hora_reunion',
        'motivo',
        'asistencia',
    ];

    protected $casts = [
        'asistencia' => 'array',
    ];
}