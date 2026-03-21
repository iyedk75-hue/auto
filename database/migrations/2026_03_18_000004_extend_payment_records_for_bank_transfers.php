<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            $table->string('payment_method')->default('manual')->after('amount');
            $table->string('transfer_reference')->nullable()->after('payment_method');
            $table->string('proof_path')->nullable()->after('transfer_reference');
            $table->string('proof_mime')->nullable()->after('proof_path');
            $table->timestamp('proof_uploaded_at')->nullable()->after('proof_mime');
            $table->foreignId('reviewed_by_user_id')->nullable()->after('proof_uploaded_at')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by_user_id');
            $table->dropColumn([
                'payment_method',
                'transfer_reference',
                'proof_path',
                'proof_mime',
                'proof_uploaded_at',
                'reviewed_at',
            ]);
        });
    }
};