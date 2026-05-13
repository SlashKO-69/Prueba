<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
           $table->string('ci_empleado')->primary();
            $table->string('nombre');
            $table->string('apaterno');
            $table->string('amaterno')->nullable();
            $table->string('celular');
            $table->string('rol'); // admin, user, etc.
            $table->string('password'); // contraseña encriptada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
