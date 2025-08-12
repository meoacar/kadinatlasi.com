<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Budget Categories
        Schema::create('budget_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // income, expense
            $table->string('icon')->nullable();
            $table->string('color')->default('#6b7280');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Budget Entries
        Schema::create('budget_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('budget_category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('type'); // income, expense
            $table->date('entry_date');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_type')->nullable(); // daily, weekly, monthly, yearly
            $table->timestamps();
        });

        // Budget Plans
        Schema::create('budget_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('monthly_income_target', 10, 2)->default(0);
            $table->decimal('monthly_expense_limit', 10, 2)->default(0);
            $table->decimal('savings_target', 10, 2)->default(0);
            $table->json('category_limits')->nullable(); // {"food": 2000, "transport": 500}
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget_plans');
        Schema::dropIfExists('budget_entries');
        Schema::dropIfExists('budget_categories');
    }
};