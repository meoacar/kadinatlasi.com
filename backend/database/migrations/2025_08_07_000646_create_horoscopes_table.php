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
        Schema::create('horoscopes', function (Blueprint $table) {
            $table->id();
            $table->string('zodiac_sign'); // Burç adı
            $table->date('date'); // Tarih
            $table->enum('type', ['daily', 'weekly', 'monthly'])->default('daily');
            $table->enum('category', ['general', 'love', 'career', 'health'])->default('general');
            $table->text('content'); // Yorum içeriği
            $table->string('source')->nullable(); // API kaynağı
            $table->timestamps();
            
            $table->unique(['zodiac_sign', 'date', 'type', 'category']);
            $table->index(['zodiac_sign', 'date', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horoscopes');
    }
};
