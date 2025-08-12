<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('size'); // XS, S, M, L, XL, XXL, 36, 37, 38, etc.
            $table->string('color')->nullable(); // Renk varyantı
            $table->decimal('price_adjustment', 8, 2)->default(0); // Fiyat farkı (+/-)
            $table->integer('stock_quantity')->default(0);
            $table->string('sku')->nullable(); // Varyant SKU
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['product_id', 'size', 'color']); // Aynı ürün için aynı beden+renk tekrarlanamaz
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};