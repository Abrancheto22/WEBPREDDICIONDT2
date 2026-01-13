<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rols', function (Blueprint $table) {
            $table->id('idrol');
            $table->string('nombre');
            // $table->timestamps(); // Asumiendo que no tiene timestamps por defecto según modelos
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('idrol')->nullable()->after('id');
            $table->foreign('idrol')->references('idrol')->on('rols');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['idrol']);
            $table->dropColumn('idrol');
        });
        Schema::dropIfExists('rols');
    }
};
