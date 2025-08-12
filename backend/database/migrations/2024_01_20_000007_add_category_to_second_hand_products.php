<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('second_hand_products', function (Blueprint $table) {
            if (!Schema::hasColumn('second_hand_products', 'category')) {
                $table->string('category')->after('images');
            }
        });
    }

    public function down()
    {
        Schema::table('second_hand_products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};