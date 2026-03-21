<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table): void {
            $table->foreignId('auto_school_id')
                ->nullable()
                ->after('id')
                ->constrained('auto_schools')
                ->nullOnDelete();
        });

        Schema::table('questions', function (Blueprint $table): void {
            $table->foreignId('auto_school_id')
                ->nullable()
                ->after('id')
                ->constrained('auto_schools')
                ->nullOnDelete();
        });

        $defaultSchoolId = DB::table('auto_schools')->orderBy('id')->value('id');

        if ($defaultSchoolId) {
            DB::table('courses')->whereNull('auto_school_id')->update(['auto_school_id' => $defaultSchoolId]);
            DB::table('questions')->whereNull('auto_school_id')->update(['auto_school_id' => $defaultSchoolId]);
        }
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table): void {
            $table->dropForeign(['auto_school_id']);
            $table->dropColumn('auto_school_id');
        });

        Schema::table('courses', function (Blueprint $table): void {
            $table->dropForeign(['auto_school_id']);
            $table->dropColumn('auto_school_id');
        });
    }
};