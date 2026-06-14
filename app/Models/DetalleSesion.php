<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleSesion extends Model
{
    protected $table = 'detalle_sesions';
    protected $primaryKey = 'id_detalle';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_sesion',
        'ci_empleado',
        'Detalles',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'ci_empleado', 'ci_empleado');
    }
}