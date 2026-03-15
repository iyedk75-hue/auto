<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('device_uuid')->nullable()->after('remember_token');
            $table->timestamp('device_bound_at')->nullable()->after('device_uuid');
            $table->timestamp('last_login_at')->nullable()->after('device_bound_at');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->string('last_user_agent')->nullable()->after('last_login_ip');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'device_uuid',
                'device_bound_at',
                'last_login_at',
                'last_login_ip',
                'last_user_agent',
            ]);
        });
    }
};
