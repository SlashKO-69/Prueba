<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    protected $table = 'sesions';
    protected $primaryKey = 'id_sesion';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ci_cliente',
        'Inicio',
        'Final',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'ci_cliente', 'Ci');
    }
}