<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flujo_Caja extends Model
{
    protected $table = 'flujo_cajas';
    protected $primaryKey = 'id_caja';
    public $incrementing = true;

    protected $fillable = [
        'asunto',
        'cantidad_dinero',
        'glosa',
        'tipo',
    ];
}
