<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('icon');
            $table->string('type'); // checkin, event, forum, special
            $table->string('condition'); // streak_7, event_5, post_10, etc
            $table->integer('target_value');
            $table->integer('points')->default(0);
            $table->string('rarity')->default('common'); // common, rare, epic, legendary
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};