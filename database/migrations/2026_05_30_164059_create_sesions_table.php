<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesions', function (Blueprint $table) {
            $table->id('id_sesion');
            $table->string('ci_cliente')->nullable();
            $table->dateTime('Inicio')->nullable();
            $table->dateTime('Final')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesions');
    }
};