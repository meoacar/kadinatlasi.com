<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('type')->default('workshop'); // workshop, seminar, webinar, meetup
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('location')->nullable();
            $table->string('online_link')->nullable();
            $table->integer('max_participants')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->boolean('is_free')->default(true);
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};