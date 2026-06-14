<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flujo_Caja extends Model
{
    protected $table = 'flujo__cajas';
    protected $primaryKey = 'id_caja';
    public $incrementing = true;

    protected $fillable = [
        'asunto',
        'cantidad_dinero',
        'glosa',
        'tipo',
        'ci_cliente',
        'ci_empleado',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'ci_cliente', 'Ci');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'ci_empleado', 'ci_empleado');
    }
}