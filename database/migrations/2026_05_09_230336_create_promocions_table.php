<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promocions', function (Blueprint $table) {
             $table->id('id_promocion'); // clave primaria autoincremental
            $table->decimal('porcentaje_descuento', 5, 2); // ejemplo: 15.50 %
            $table->string('requisito'); // texto del requisito
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promocions');
    }
};
