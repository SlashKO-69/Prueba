<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sueldo_o__pagos', function (Blueprint $table) {
            $table->string('ci_empleado')->nullable()->after('id_sueldo');
            $table->decimal('monto', 8, 2)->default(2000)->after('ci_empleado');
            $table->foreign('ci_empleado')->references('ci_empleado')->on('empleados')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('sueldo_o__pagos', function (Blueprint $table) {
            $table->dropForeign(['ci_empleado']);
            $table->dropColumn(['ci_empleado', 'monto']);
        });
    }
};