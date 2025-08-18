<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BudgetCategory;

class BudgetCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // Income Categories
            ['name' => 'Maaş', 'type' => 'income', 'icon' => '💰', 'color' => '#10b981', 'is_default' => true],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => '💻', 'color' => '#3b82f6', 'is_default' => true],
            ['name' => 'Yatırım', 'type' => 'income', 'icon' => '📈', 'color' => '#8b5cf6', 'is_default' => true],
            ['name' => 'Diğer Gelir', 'type' => 'income', 'icon' => '💵', 'color' => '#06b6d4', 'is_default' => true],

            // Expense Categories
            ['name' => 'Yiyecek & İçecek', 'type' => 'expense', 'icon' => '🍽️', 'color' => '#ef4444', 'is_default' => true],
            ['name' => 'Ulaşım', 'type' => 'expense', 'icon' => '🚗', 'color' => '#f97316', 'is_default' => true],
            ['name' => 'Barınma', 'type' => 'expense', 'icon' => '🏠', 'color' => '#84cc16', 'is_default' => true],
            ['name' => 'Sağlık', 'type' => 'expense', 'icon' => '🏥', 'color' => '#ec4899', 'is_default' => true],
            ['name' => 'Eğlence', 'type' => 'expense', 'icon' => '🎉', 'color' => '#8b5cf6', 'is_default' => true],
            ['name' => 'Alışveriş', 'type' => 'expense', 'icon' => '🛍️', 'color' => '#f59e0b', 'is_default' => true],
            ['name' => 'Eğitim', 'type' => 'expense', 'icon' => '📚', 'color' => '#3b82f6', 'is_default' => true],
            ['name' => 'Faturalar', 'type' => 'expense', 'icon' => '📄', 'color' => '#6b7280', 'is_default' => true],
            ['name' => 'Tasarruf', 'type' => 'expense', 'icon' => '🏦', 'color' => '#10b981', 'is_default' => true],
            ['name' => 'Diğer Gider', 'type' => 'expense', 'icon' => '💸', 'color' => '#ef4444', 'is_default' => true],
        ];

        foreach ($categories as $category) {
            BudgetCategory::create($category);
        }
    }
}