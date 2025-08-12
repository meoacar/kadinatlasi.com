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
        Schema::create('pregnancy_weeks', function (Blueprint $table) {
            $table->id();
            $table->integer('week_number');
            $table->string('title');
            $table->text('description');
            $table->text('baby_development');
            $table->text('mother_changes');
            $table->json('tips')->nullable();
            $table->string('baby_size')->nullable();
            $table->string('baby_weight')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregnancy_weeks');
    }
};
