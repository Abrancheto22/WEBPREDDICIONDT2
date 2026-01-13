<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cita', function (Blueprint $table) {
            $table->id('idcita');
            $table->date('fecha_cita')->nullable();
            $table->time('hora_cita')->nullable();
            $table->text('motivo')->nullable();
            $table->string('estado')->default('Pendiente');
            
            $table->unsignedBigInteger('idpaciente')->nullable();
            $table->unsignedBigInteger('iddoctor')->nullable();
            $table->unsignedBigInteger('idenfermera')->nullable();

            $table->foreign('idpaciente')->references('idpaciente')->on('paciente');
            $table->foreign('iddoctor')->references('iddoctor')->on('doctor');
            $table->foreign('idenfermera')->references('idenfermera')->on('efermera');
        });

        Schema::create('triaje', function (Blueprint $table) {
            $table->id('idtriaje');
            $table->unsignedBigInteger('idcita');
            $table->integer('edad')->nullable();
            $table->float('talla')->nullable();
            $table->float('peso')->nullable();
            $table->float('BMI')->nullable();
            $table->float('grosor_piel')->nullable();
            $table->text('observaciones')->nullable();

            $table->foreign('idcita')->references('idcita')->on('cita')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('triaje');
        Schema::dropIfExists('cita');
    }
};
