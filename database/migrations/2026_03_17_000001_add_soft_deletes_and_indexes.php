<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('payment_records', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('status');
        });

        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('status');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['status']);
        });

        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['status']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
    }
};
