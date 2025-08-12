<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beauty_articles', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained('beauty_categories')->onDelete('set null');
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('beauty_articles', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->enum('category', ['skincare', 'makeup', 'haircare', 'nails', 'fragrance'])->after('read_time');
        });
    }
};