<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tasks System
        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description');
                $table->string('icon')->nullable();
                $table->enum('type', ['daily', 'weekly', 'monthly', 'special']);
                $table->enum('category', ['health', 'social', 'learning', 'wellness', 'engagement']);
                $table->integer('points')->default(10);
                $table->integer('xp_reward')->default(5);
                $table->json('requirements')->nullable();
                $table->string('action_type');
                $table->integer('target_count')->default(1);
                $table->boolean('is_active')->default(true);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // User Tasks
        if (!Schema::hasTable('user_tasks')) {
            Schema::create('user_tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('task_id')->constrained()->onDelete('cascade');
                $table->integer('progress')->default(0);
                $table->integer('target')->default(1);
                $table->boolean('is_completed')->default(false);
                $table->timestamp('completed_at')->nullable();
                $table->date('task_date');
                $table->json('metadata')->nullable();
                $table->timestamps();
                
                $table->unique(['user_id', 'task_id', 'task_date']);
            });
        }

        // User Levels
        if (!Schema::hasTable('user_levels')) {
            Schema::create('user_levels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('level')->default(1);
                $table->integer('current_xp')->default(0);
                $table->integer('total_xp')->default(0);
                $table->integer('points')->default(0);
                $table->integer('achievements_count')->default(0);
                $table->integer('tasks_completed')->default(0);
                $table->integer('daily_streak')->default(0);
                $table->integer('max_streak')->default(0);
                $table->date('last_activity_date')->nullable();
                $table->json('statistics')->nullable();
                $table->timestamps();
                
                $table->unique('user_id');
            });
        }

        // Leaderboards
        if (!Schema::hasTable('leaderboards')) {
            Schema::create('leaderboards', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->enum('type', ['points', 'level', 'achievements', 'streak']);
                $table->enum('period', ['daily', 'weekly', 'monthly', 'all_time']);
                $table->boolean('is_active')->default(true);
                $table->integer('max_entries')->default(100);
                $table->timestamps();
            });
        }

        // Leaderboard Entries
        if (!Schema::hasTable('leaderboard_entries')) {
            Schema::create('leaderboard_entries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('leaderboard_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('rank');
                $table->integer('score');
                $table->date('period_date');
                $table->json('metadata')->nullable();
                $table->timestamps();
                
                $table->unique(['leaderboard_id', 'user_id', 'period_date']);
            });
        }

        // Rewards System
        if (!Schema::hasTable('rewards')) {
            Schema::create('rewards', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description');
                $table->enum('type', ['badge', 'title', 'avatar_frame', 'discount', 'premium_access']);
                $table->string('icon')->nullable();
                $table->string('image')->nullable();
                $table->integer('cost_points')->default(0);
                $table->integer('required_level')->default(1);
                $table->boolean('is_active')->default(true);
                $table->boolean('is_limited')->default(false);
                $table->integer('stock')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        // User Rewards
        if (!Schema::hasTable('user_rewards')) {
            Schema::create('user_rewards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('reward_id')->constrained()->onDelete('cascade');
                $table->boolean('is_equipped')->default(false);
                $table->timestamp('obtained_at');
                $table->timestamps();
                
                $table->unique(['user_id', 'reward_id']);
            });
        }

        // Activity Log for tracking user actions
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('action_type');
                $table->string('action_target')->nullable();
                $table->integer('action_target_id')->nullable();
                $table->json('metadata')->nullable();
                $table->integer('points_earned')->default(0);
                $table->integer('xp_earned')->default(0);
                $table->timestamps();
                
                $table->index(['user_id', 'action_type', 'created_at']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('user_rewards');
        Schema::dropIfExists('rewards');
        Schema::dropIfExists('leaderboard_entries');
        Schema::dropIfExists('leaderboards');
        Schema::dropIfExists('user_levels');
        Schema::dropIfExists('user_tasks');
        Schema::dropIfExists('tasks');
    }
};