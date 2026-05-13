<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'Ci';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'Ci',
        'nombre',
        'apaterno',
        'amaterno',
    ];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'ci_cliente', 'Ci');
    }
}