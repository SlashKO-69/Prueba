<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_sesions', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sesion')->nullable()->after('id_detalle');
            $table->string('ci_empleado')->nullable()->after('id_sesion');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_sesions', function (Blueprint $table) {
            $table->dropColumn(['id_sesion', 'ci_empleado']);
        });
    }
};
