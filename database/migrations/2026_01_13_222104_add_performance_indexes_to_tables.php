<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Índices para tabla 'paciente'
        DB::statement("CREATE INDEX IF NOT EXISTS paciente_iduser_index ON paciente (iduser)");
        DB::statement("CREATE INDEX IF NOT EXISTS paciente_dni_index ON paciente (\"DNI\")");

        // Índices para tabla 'doctor'
        DB::statement("CREATE INDEX IF NOT EXISTS doctor_iduser_index ON doctor (iduser)");

        // Índices para tabla 'cita'
        DB::statement("CREATE INDEX IF NOT EXISTS cita_idpaciente_index ON cita (idpaciente)");
        DB::statement("CREATE INDEX IF NOT EXISTS cita_iddoctor_index ON cita (iddoctor)");
        DB::statement("CREATE INDEX IF NOT EXISTS cita_idenfermera_index ON cita (idenfermera)");
        DB::statement("CREATE INDEX IF NOT EXISTS cita_fecha_cita_index ON cita (fecha_cita)");
        DB::statement("CREATE INDEX IF NOT EXISTS cita_estado_index ON cita (estado)");

        // Índices para tabla 'triaje'
        DB::statement("CREATE INDEX IF NOT EXISTS triaje_idcita_index ON triaje (idcita)");

        // Índices para tabla 'prediccion'
        DB::statement("CREATE INDEX IF NOT EXISTS prediccion_idcita_index ON prediccion (idcita)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP INDEX IF EXISTS paciente_iduser_index");
        DB::statement("DROP INDEX IF EXISTS paciente_dni_index");
        DB::statement("DROP INDEX IF EXISTS doctor_iduser_index");
        DB::statement("DROP INDEX IF EXISTS cita_idpaciente_index");
        DB::statement("DROP INDEX IF EXISTS cita_iddoctor_index");
        DB::statement("DROP INDEX IF EXISTS cita_idenfermera_index");
        DB::statement("DROP INDEX IF EXISTS cita_fecha_cita_index");
        DB::statement("DROP INDEX IF EXISTS cita_estado_index");
        DB::statement("DROP INDEX IF EXISTS triaje_idcita_index");
        DB::statement("DROP INDEX IF EXISTS prediccion_idcita_index");
    }
};
