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
        Schema::table('prediccion', function (Blueprint $table) {
            $table->float('insulina')->change();
            $table->float('pedigree')->change();
            $table->float('BMI')->change();
            $table->float('glucosa')->change();
            $table->float('presion_sanguinea')->change();
            $table->float('grosor_piel')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prediccion', function (Blueprint $table) {
            $table->integer('insulina')->change();
            $table->decimal('pedigree', 8, 2)->change(); // Assuming it was decimal or similar before
            $table->decimal('BMI', 8, 2)->change();
            $table->integer('glucosa')->change();
            $table->integer('presion_sanguinea')->change();
            $table->integer('grosor_piel')->change();
        });
    }
};
