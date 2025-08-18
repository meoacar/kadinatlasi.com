<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt');
            $table->longText('content');
            $table->string('icon', 10)->default('✨');
            $table->string('color', 20)->default('#fce7f3');
            $table->integer('read_time')->default(5);
            $table->enum('category', ['skincare', 'makeup', 'haircare', 'nails', 'fragrance']);
            $table->string('featured_image')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_articles');
    }
};