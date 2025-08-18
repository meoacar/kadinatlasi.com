<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('brand')->nullable();
            $table->string('icon', 10)->default('🧴');
            $table->string('color', 20)->default('#fce7f3');
            $table->decimal('rating', 2, 1)->default(4.5);
            $table->decimal('price', 8, 2)->nullable();
            $table->enum('category', ['cleanser', 'moisturizer', 'sunscreen', 'serum', 'makeup', 'haircare']);
            $table->json('ingredients')->nullable();
            $table->json('pros')->nullable();
            $table->json('cons')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_products');
    }
};