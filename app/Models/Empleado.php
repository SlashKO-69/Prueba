<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash; 
class Empleado extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'ci_empleado';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ci_empleado',
        'nombre',
        'apaterno',
        'amaterno',
        'celular',
        'rol',
        'password',
    ];
     // Solo hashea la contraseña si se proporciona (para admins)
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
