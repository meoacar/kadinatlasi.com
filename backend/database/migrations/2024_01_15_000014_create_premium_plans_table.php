<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('premium_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_id')->unique(); // basic, premium, vip
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->string('duration')->default('monthly');
            $table->boolean('is_popular')->default(false);
            $table->json('features');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('premium_plans');
    }
};