<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediccion', function (Blueprint $table) {
            $table->id('idprediccion');
            $table->unsignedBigInteger('idcita');
            
            // Usamos float directamente para evitar el problema de conversión posterior
            $table->float('glucosa')->nullable();
            $table->float('presion_sanguinea')->nullable();
            $table->float('grosor_piel')->nullable();
            $table->float('insulina')->nullable();
            $table->float('BMI')->nullable();
            $table->float('pedigree')->nullable();
            
            $table->integer('embarazos')->nullable();
            $table->integer('edad')->nullable();
            
            $table->float('resultado')->nullable(); // Probabilidad
            $table->text('observacion')->nullable();
            
            // Timers
            $table->string('timer')->nullable();
            $table->string('timer_inicio')->nullable();
            $table->string('timer_parada')->nullable();

            $table->boolean('validar_prediccion')->default(false);

            $table->foreign('idcita')->references('idcita')->on('cita')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediccion');
    }
};
