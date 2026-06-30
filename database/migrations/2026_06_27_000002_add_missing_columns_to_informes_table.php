<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('informes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_aparato')->nullable()->after('id');
            $table->string('nombre_maquina')->nullable()->after('id_aparato');
            $table->string('ci_empleado')->nullable()->after('nombre_maquina');
            $table->boolean('leido')->default(false)->after('fecha_informe');
        });
    }

    public function down(): void
    {
        Schema::table('informes', function (Blueprint $table) {
            $table->dropColumn(['id_aparato', 'nombre_maquina', 'ci_empleado', 'leido']);
        });
    }
};
