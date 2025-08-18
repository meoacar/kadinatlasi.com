<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('video_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('duration', 10)->nullable();
            $table->integer('views_count')->default(0);
            $table->foreignId('category_id')->nullable()->constrained('beauty_categories')->onDelete('set null');
            $table->json('tags')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_videos');
    }
};