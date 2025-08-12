<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('forum_topics')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('forum_groups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->string('question');
            $table->json('options'); // ['Seçenek 1', 'Seçenek 2', ...]
            $table->boolean('multiple_choice')->default(false);
            $table->boolean('anonymous')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('forum_poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained('forum_polls')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->json('selected_options'); // [0, 2] (option indexes)
            $table->timestamps();
            
            $table->unique(['poll_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_poll_votes');
        Schema::dropIfExists('forum_polls');
    }
};