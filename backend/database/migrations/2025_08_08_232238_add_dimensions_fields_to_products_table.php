<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('dimensions_length', 8, 2)->nullable()->after('dimensions');
            $table->decimal('dimensions_width', 8, 2)->nullable()->after('dimensions_length');
            $table->decimal('dimensions_height', 8, 2)->nullable()->after('dimensions_width');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['dimensions_length', 'dimensions_width', 'dimensions_height']);
        });
    }
};