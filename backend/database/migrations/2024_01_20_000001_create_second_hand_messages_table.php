<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('second_hand_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('second_hand_products')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            $table->index(['product_id', 'created_at']);
            $table->index(['receiver_id', 'is_read']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('second_hand_messages');
    }
};