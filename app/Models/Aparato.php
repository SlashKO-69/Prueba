<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aparato extends Model
{
    protected $table = 'aparatos';
    protected $primaryKey = 'id_aparato';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre_aparato',
        'tipo_aparato',
        'estado_aparato',
    ];

    public function informes()
    {
        return $this->hasMany(Informe::class, 'id_aparato', 'id_aparato');
    }
}