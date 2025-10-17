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
                        $table->json('attachment_paths')->nullable()->after('observacion');
            $table->json('attachment_names')->nullable()->after('attachment_paths');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prediccion', function (Blueprint $table) {
                        $table->dropColumn(['attachment_paths', 'attachment_names']);
        });
    }
};
