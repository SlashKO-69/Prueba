<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flujo__cajas', function (Blueprint $table) {
            $table->string('ci_empleado')->nullable()->after('ci_cliente');
        });
    }

    public function down(): void
    {
        Schema::table('flujo__cajas', function (Blueprint $table) {
            $table->dropColumn('ci_empleado');
        });
    }
};
