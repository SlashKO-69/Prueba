<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sueldo_o__pagos', function (Blueprint $table) {
             $table->id('id_sueldo');
                $table->date('fecha_pago');
                $table->string('estado');
                $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sueldo_o__pagos');
    }
};
