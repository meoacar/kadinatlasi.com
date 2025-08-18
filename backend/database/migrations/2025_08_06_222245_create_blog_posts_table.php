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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->json('interests')->nullable(); // ['gebelik', 'moda', 'diyet', 'astroloji']
            $table->json('health_info')->nullable(); // Sağlık bilgileri
            $table->date('last_period_date')->nullable();
            $table->integer('cycle_length')->default(28);
            $table->json('goals')->nullable(); // Hedefler (kilo, su içme vb.)
            $table->json('achievements')->nullable(); // Rozetler ve başarımlar
            $table->integer('points')->default(0);
            $table->integer('level')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
