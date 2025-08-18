<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diet_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('type', ['weight_loss', 'weight_gain', 'maintenance', 'muscle_gain']);
            $table->integer('daily_calories');
            $table->json('macros'); // protein, carbs, fat percentages
            $table->json('meal_plan'); // Daily meal structure
            $table->boolean('dietitian_approved')->default(false);
            $table->string('dietitian_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diet_plans');
    }
};