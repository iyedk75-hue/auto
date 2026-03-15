<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('auto_school_id')
                ->nullable()
                ->constrained('auto_schools')
                ->nullOnDelete();
            $table->string('phone')->nullable();
            $table->string('status')->default('active');
            $table->decimal('balance_due', 10, 2)->default(0);
            $table->timestamp('registered_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['auto_school_id']);
            $table->dropColumn(['auto_school_id', 'phone', 'status', 'balance_due', 'registered_at']);
        });
    }
};
