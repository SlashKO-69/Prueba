<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reunions', function (Blueprint $table) {
            $table->id('id_reunion');
            $table->date('fecha_reunion');
            $table->time('hora_reunion');
            $table->string('motivo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reunions');
    }
};
