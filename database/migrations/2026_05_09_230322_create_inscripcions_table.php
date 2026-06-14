<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripcions', function (Blueprint $table) {
            $table->id();
            $table->string('ci_cliente')->nullable();
            $table->foreign('ci_cliente')->references('Ci')->on('clientes')->onDelete('cascade');
            $table->unsignedBigInteger('id_promocion')->nullable();
            $table->date('fecha_inscripcion');
            $table->date('fecha_vencimiento');
            $table->decimal('monto', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripcions');
    }
};