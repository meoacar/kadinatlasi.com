<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_outfits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['casual', 'business', 'evening', 'sport']);
            $table->enum('season', ['spring', 'summer', 'autumn', 'winter']);
            $table->integer('likes_count')->default(0);
            $table->boolean('is_approved')->default(false);
            $table->string('featured_image')->nullable();
            $table->json('outfit_items')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_outfits');
    }
};