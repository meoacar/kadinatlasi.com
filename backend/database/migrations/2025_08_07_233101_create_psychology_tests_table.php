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
        Schema::create('psychology_tests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['personality', 'stress', 'anxiety', 'depression', 'self_esteem']);
            $table->json('questions'); // [{"question": "...", "options": [...], "weight": 1}]
            $table->json('results'); // [{"min_score": 0, "max_score": 10, "title": "...", "description": "..."}]
            $table->integer('duration_minutes');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychology_tests');
    }
};
