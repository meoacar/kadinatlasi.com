<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false)->after('published_at');
            $table->enum('premium_type', ['basic', 'premium', 'vip'])->nullable()->after('is_premium');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['is_premium', 'premium_type']);
        });
    }
};