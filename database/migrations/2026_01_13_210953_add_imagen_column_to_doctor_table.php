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
        Schema::table('doctor', function (Blueprint $table) {
            if (!Schema::hasColumn('doctor', 'imagen')) {
                $table->string('imagen')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor', function (Blueprint $table) {
            if (Schema::hasColumn('doctor', 'imagen')) {
                $table->dropColumn('imagen');
            }
        });
    }
};
