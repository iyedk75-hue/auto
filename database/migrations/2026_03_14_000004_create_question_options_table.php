<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->uuid('question_id');
            $table->string('option_id', 2);
            $table->string('text');
            $table->timestamps();

            $table->unique(['question_id', 'option_id']);
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
