<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('second_hand_products', function (Blueprint $table) {
            if (!Schema::hasColumn('second_hand_products', 'seller_name')) {
                $table->string('seller_name')->after('user_id');
            }
            if (!Schema::hasColumn('second_hand_products', 'seller_email')) {
                $table->string('seller_email')->after('seller_name');
            }
            if (!Schema::hasColumn('second_hand_products', 'seller_phone')) {
                $table->string('seller_phone')->nullable()->after('seller_email');
            }
        });
    }

    public function down()
    {
        Schema::table('second_hand_products', function (Blueprint $table) {
            $table->dropColumn(['seller_name', 'seller_email', 'seller_phone']);
        });
    }
};