<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('second_hand_products', function (Blueprint $table) {
            // Make nullable fields that might not always be filled
            $table->string('name')->nullable()->change();
            $table->string('slug')->nullable()->change();
            $table->integer('seller_id')->nullable()->change();
            $table->integer('category_id')->nullable()->change();
            $table->text('contact_info')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('second_hand_products', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('slug')->nullable(false)->change();
            $table->integer('seller_id')->nullable(false)->change();
            $table->integer('category_id')->nullable(false)->change();
            $table->text('contact_info')->nullable(false)->change();
        });
    }
};