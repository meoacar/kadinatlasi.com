<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('category', ['Kahvaltı', 'Öğle Yemeği', 'Akşam Yemeği', 'Ara Öğün', 'Tatlı']);
            $table->integer('prep_time'); // dakika
            $table->integer('cook_time'); // dakika
            $table->integer('servings');
            $table->integer('calories_per_serving');
            $table->json('ingredients');
            $table->json('instructions');
            $table->json('nutrition_info'); // protein, carbs, fat, fiber
            $table->json('diet_type'); // vegan, vegetarian, gluten-free, etc.
            $table->string('image_url')->nullable();
            $table->enum('difficulty', ['kolay', 'orta', 'zor']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};