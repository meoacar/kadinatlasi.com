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
        Schema::create('mood_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('mood', ['very_sad', 'sad', 'neutral', 'happy', 'very_happy']);
            $table->integer('energy_level')->default(5); // 1-10
            $table->integer('stress_level')->default(5); // 1-10
            $table->text('notes')->nullable();
            $table->json('activities')->nullable(); // ['exercise', 'meditation', 'work']
            $table->timestamps();
            
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_trackers');
    }
};
