<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('second_hand_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('second_hand_products')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned(); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'reviewer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('second_hand_reviews');
    }
};