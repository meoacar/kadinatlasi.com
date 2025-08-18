<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('instructor_name');
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('duration'); // minutes
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->string('category');
            $table->string('image_url')->nullable();
            $table->string('video_url')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('enrollment_count')->default(0);
            $table->decimal('rating', 2, 1)->default(0);
            $table->timestamps();

            $table->foreign('instructor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};