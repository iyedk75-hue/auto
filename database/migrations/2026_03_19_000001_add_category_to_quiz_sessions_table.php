<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_sessions', function (Blueprint $table): void {
            $table->string('question_category')->nullable()->after('difficulty');
            $table->index('question_category');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_sessions', function (Blueprint $table): void {
            $table->dropIndex(['question_category']);
            $table->dropColumn('question_category');
        });
    }
};