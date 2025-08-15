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
        Schema::create('admin_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('action', 50); // login, logout, create, update, delete, view
            $table->string('model_type')->nullable(); // App\Models\User, App\Models\Product, etc.
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable(); // Eski değerler
            $table->json('new_values')->nullable(); // Yeni değerler
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE
            $table->text('description')->nullable(); // Özel açıklama
            $table->enum('severity', ['low', 'medium', 'high'])->default('low');
            $table->json('tags')->nullable(); // Etiketler
            $table->timestamps();

            // İndeksler
            $table->index(['admin_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['severity', 'created_at']);
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activities');
    }
};