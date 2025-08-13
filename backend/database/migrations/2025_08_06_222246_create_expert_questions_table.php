<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expert_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('expert_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->text('question');
            $table->text('answer')->nullable();
            $table->enum('status', ['pending', 'answered', 'closed'])->default('pending');
            $table->boolean('is_public')->default(false);
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expert_questions');
    }
};