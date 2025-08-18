<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_tips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->string('icon', 10)->default('💡');
            $table->string('color', 20)->default('#fbbf24');
            $table->enum('category', ['skincare', 'makeup', 'haircare', 'nails', 'lifestyle']);
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->string('time_required')->nullable();
            $table->json('ingredients')->nullable();
            $table->json('steps')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_tips');
    }
};