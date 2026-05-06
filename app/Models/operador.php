<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class operador extends Model
{  protected $table = 'operadors';
   protected $fillable = ['nombre','rol','contraseña',
    ];
    // Opcional: ocultar el campo contraseña cuando conviertas a JSON
}
