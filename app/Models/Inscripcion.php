<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripcions';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ci_cliente',
        'id_promocion',
        'fecha_inscripcion',
        'fecha_vencimiento',
        'monto',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'ci_cliente', 'Ci');
    }

    public function promocion()
    {
        return $this->belongsTo(Promocion::class, 'id_promocion', 'id_promocion');
    }
}