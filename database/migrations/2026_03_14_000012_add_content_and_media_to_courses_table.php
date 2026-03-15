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
        Schema::table('courses', function (Blueprint $table) {
            $table->longText('content')->nullable()->after('description');
            $table->string('media_path')->nullable()->after('duration_minutes');
            $table->string('media_mime')->nullable()->after('media_path');
            $table->string('pdf_path')->nullable()->after('media_mime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['content', 'media_path', 'media_mime', 'pdf_path']);
        });
    }
};
