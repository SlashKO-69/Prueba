<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
     protected $table = 'promocions';
    protected $primaryKey = 'id_promocion';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'porcentaje_descuento',
        'requisito',
    ];
}
