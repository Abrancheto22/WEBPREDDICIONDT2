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
        // 1. Corregir tabla 'efermera' (Agregar timestamps)
        Schema::table('efermera', function (Blueprint $table) {
            if (!Schema::hasColumn('efermera', 'created_at')) {
                $table->timestamps();
            }
        });

        // 2. Corregir tabla 'cita' (Agregar timestamps)
        Schema::table('cita', function (Blueprint $table) {
            if (!Schema::hasColumn('cita', 'created_at')) {
                $table->timestamps();
            }
        });

        // 3. Corregir tabla 'triaje' (Agregar timestamps y columnas faltantes para predicción)
        Schema::table('triaje', function (Blueprint $table) {
            if (!Schema::hasColumn('triaje', 'created_at')) {
                $table->timestamps();
            }
            if (!Schema::hasColumn('triaje', 'embarazos')) {
                $table->integer('embarazos')->nullable();
            }
            if (!Schema::hasColumn('triaje', 'glucosa')) {
                $table->float('glucosa')->nullable();
            }
            if (!Schema::hasColumn('triaje', 'presion_sanguinea')) {
                $table->float('presion_sanguinea')->nullable();
            }
            if (!Schema::hasColumn('triaje', 'insulina')) {
                $table->float('insulina')->nullable();
            }
            if (!Schema::hasColumn('triaje', 'pedigree')) {
                $table->float('pedigree')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('efermera', function (Blueprint $table) {
            if (Schema::hasColumn('efermera', 'created_at')) {
                $table->dropTimestamps();
            }
        });

        Schema::table('cita', function (Blueprint $table) {
            if (Schema::hasColumn('cita', 'created_at')) {
                $table->dropTimestamps();
            }
        });

        Schema::table('triaje', function (Blueprint $table) {
            if (Schema::hasColumn('triaje', 'created_at')) {
                $table->dropTimestamps();
            }
            $table->dropColumn(['embarazos', 'glucosa', 'presion_sanguinea', 'insulina', 'pedigree']);
        });
    }
};
