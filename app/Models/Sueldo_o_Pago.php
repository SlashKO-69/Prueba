<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sueldo_o_Pago extends Model
{
    protected $table = 'sueldo_o__pagos';
    protected $primaryKey = 'id_sueldo';
    public $incrementing = true;

    protected $fillable = [
        'ci_empleado',
        'monto',
        'fecha_pago',
        'estado',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'ci_empleado', 'ci_empleado');
    }
}