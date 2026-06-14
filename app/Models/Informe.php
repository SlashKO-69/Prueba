<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    protected $table = 'informes';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'id_aparato',
        'nombre_maquina',
        'detalle',
        'fecha_informe',
        'ci_empleado',
        'leido',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'ci_empleado', 'ci_empleado');
    }

    public function aparato()
    {
        return $this->belongsTo(Aparato::class, 'id_aparato', 'id_aparato');
    }
}