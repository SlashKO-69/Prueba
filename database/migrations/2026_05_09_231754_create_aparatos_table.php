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
        Schema::create('aparatos', function (Blueprint $table) {
           $table->id('id_aparato');
        $table->string('nombre_aparato');
        $table->string('tipo_aparato');
        $table->string('estado_aparato');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aparatos');
    }
};
