<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor', function (Blueprint $table) {
            $table->id('iddoctor');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('DNI', 20)->unique()->nullable();
            $table->string('especialidad')->nullable();
            $table->decimal('sueldo', 10, 2)->nullable();
            $table->foreignId('iduser')->nullable()->constrained('users');
            // $table->timestamps();
        });

        // Nota: El modelo usa 'efermera' como nombre de tabla
        Schema::create('efermera', function (Blueprint $table) {
            $table->id('idenfermera');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('DNI', 20)->unique()->nullable();
            $table->string('numero')->nullable(); // Telefono
            $table->string('imagen')->nullable();
            $table->foreignId('iduser')->nullable()->constrained('users');
            // $table->timestamps();
        });

        Schema::create('paciente', function (Blueprint $table) {
            $table->id('idpaciente');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('DNI', 20)->unique()->nullable();
            $table->string('sexo')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('direccion')->nullable(); // Agregado
            $table->string('telefono')->nullable();
            $table->string('imagen')->nullable(); // Agregado para coincidir con la inserción
            $table->foreignId('iduser')->nullable()->constrained('users');
            $table->timestamps(); // Agregado para coincidir con la inserción
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paciente');
        Schema::dropIfExists('efermera');
        Schema::dropIfExists('doctor');
    }
};
