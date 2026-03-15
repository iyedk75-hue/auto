<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('courses');
    }

    public function down(): void
    {
        // Legacy learning tables were intentionally removed.
    }
};
