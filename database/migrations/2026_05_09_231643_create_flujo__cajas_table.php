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
        Schema::create('flujo__cajas', function (Blueprint $table) {
            $table->id('id_caja');
            $table->string('asunto');
            $table->decimal('cantidad_dinero', 10, 2);
            $table->string('glosa')->nullable();
            $table->string('tipo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flujo__cajas');
    }
};
