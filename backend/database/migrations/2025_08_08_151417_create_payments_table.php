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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->string('payment_id')->unique(); // İyzico payment ID
            $table->string('conversation_id')->nullable(); // İyzico conversation ID
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3)->default('TRY');
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->enum('payment_method', ['credit_card', 'debit_card', 'bank_transfer'])->default('credit_card');
            $table->string('gateway')->default('iyzico'); // Ödeme sağlayıcısı
            $table->json('gateway_response')->nullable(); // İyzico response
            $table->string('failure_reason')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
