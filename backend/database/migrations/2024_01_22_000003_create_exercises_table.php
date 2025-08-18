<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('category', ['Kardiyovasküler', 'Güç Antrenmanı', 'Esneklik', 'Yoga', 'Pilates', 'HIIT']);
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced']);
            $table->integer('duration_minutes');
            $table->integer('calories_burned')->nullable();
            $table->string('equipment_needed')->nullable();
            $table->text('instructions');
            $table->string('video_url')->nullable();
            $table->string('image_url')->nullable();
            $table->json('muscle_groups')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};