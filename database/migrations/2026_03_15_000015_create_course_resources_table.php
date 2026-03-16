<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->string('resource_type');
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->longText('note_body')->nullable();
            $table->longText('note_body_ar')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_mime')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->cascadeOnDelete();

            $table->index(['course_id', 'sort_order']);
            $table->index(['course_id', 'resource_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_resources');
    }
};
