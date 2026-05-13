<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';
    protected $primaryKey = 'id_horario';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'fecha',
        'hora_entrada',
        'hora_salida',
        'turno',
    ];
}
