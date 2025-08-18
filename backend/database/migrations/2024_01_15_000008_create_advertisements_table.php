<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['banner', 'sidebar', 'popup', 'sponsored_content']);
            $table->enum('position', ['header', 'sidebar', 'footer', 'content', 'popup']);
            $table->text('content')->nullable();
            $table->string('image_url')->nullable();
            $table->string('link_url')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price', 10, 2);
            $table->integer('clicks')->default(0);
            $table->integer('impressions')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('client_name');
            $table->string('client_email');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};