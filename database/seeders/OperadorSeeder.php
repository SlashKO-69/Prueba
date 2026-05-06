<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OperadorSeeder extends Seeder
{
        public function run()
        {
            // Administradora con contraseña:
        // Este registro crea a la administradora base del sistema.
        // Usamos Hash::make para encriptar la contraseña "123456" y no guardarla en texto plano.
        // Caso de uso: cuando quieras tener siempre un usuario administrador inicial para entrar al sistema.
        DB::table('operadors')->insert([
            'nombre' => 'Annie',
            'rol' => 'administradora',
            'contraseña' => Hash::make('123456'),
        ]);

        // Recepcionista sin contraseña:
        // Este registro crea a un recepcionista que no necesita contraseña para entrar.
        // Se deja el campo "contraseña" como null porque ese rol no requiere validación de clave.
        // Caso de uso: cuando quieras probar el flujo de login de recepcionista sin complicarte con contraseñas.
        DB::table('operadors')->insert([
            'nombre' => 'Recepcionista Gym',
            'rol' => 'recepcionista',
            'contraseña' => null,
        ]);
    }
}
