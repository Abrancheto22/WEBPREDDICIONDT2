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
            if (!Schema::hasColumn('doctor', 'numero')) {
                $table->string('numero')->nullable();
            }
            if (!Schema::hasColumn('doctor', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor', function (Blueprint $table) {
            if (Schema::hasColumn('doctor', 'numero')) {
                $table->dropColumn('numero');
            }
            if (Schema::hasColumn('doctor', 'created_at')) {
                $table->dropTimestamps();
            }
        });
    }
};
