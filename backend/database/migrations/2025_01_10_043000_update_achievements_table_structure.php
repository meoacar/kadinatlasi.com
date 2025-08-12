<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop and recreate achievements table with new structure
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
        
        // Recreate achievements table
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('achievement_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('badge_color', 7)->default('#E57399');
            $table->enum('type', ['one_time', 'repeatable', 'progressive']);
            $table->enum('difficulty', ['bronze', 'silver', 'gold', 'platinum', 'diamond']);
            $table->integer('points')->default(0);
            $table->json('requirements')->nullable();
            $table->integer('target_value')->nullable();
            $table->string('target_metric')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_hidden')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Recreate user_achievements table
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->integer('progress')->default(0);
            $table->integer('target')->default(1);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'achievement_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
        
        // Restore original achievements table structure if needed
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('type');
            $table->string('condition');
            $table->integer('target_value')->nullable();
            $table->integer('points')->default(0);
            $table->string('rarity');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};