<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        $recipes = [
            [
                'name' => 'Avokadolu Tost',
                'description' => 'Sağlıklı yağlar içeren besleyici kahvaltı',
                'category' => 'breakfast',
                'diet_type' => 'vegetarian',
                'prep_time_minutes' => 5,
                'cook_time_minutes' => 5,
                'servings' => 1,
                'calories_per_serving' => 320,
                'protein' => 12.5,
                'carbs' => 35.0,
                'fat' => 18.0,
                'ingredients' => [
                    '1 dilim tam buğday ekmeği',
                    '1/2 avokado',
                    '1 yumurta',
                    'Tuz, karabiber',
                    'Limon suyu'
                ],
                'instructions' => 'Ekmeği kızartın. Avokadoyu ezin, limon suyu ekleyin. Yumurtayı haşlayın. Tost üzerine avokado ve yumurtayı yerleştirin.',
                'difficulty' => 'easy',
                'is_popular' => true
            ],
            [
                'name' => 'Quinoa Salatası',
                'description' => 'Protein açısından zengin, tok tutan salata',
                'category' => 'lunch',
                'diet_type' => 'vegan',
                'prep_time_minutes' => 15,
                'cook_time_minutes' => 15,
                'servings' => 2,
                'calories_per_serving' => 280,
                'protein' => 10.0,
                'carbs' => 45.0,
                'fat' => 8.0,
                'ingredients' => [
                    '1 su bardağı quinoa',
                    '1 salatalık',
                    '2 domates',
                    '1/4 kırmızı soğan',
                    'Maydanoz',
                    'Zeytinyağı',
                    'Limon suyu'
                ],
                'instructions' => 'Quinoayı haşlayın. Sebzeleri doğrayın. Tüm malzemeleri karıştırın, zeytinyağı ve limon suyu ile tatlandırın.',
                'difficulty' => 'easy',
                'is_popular' => true
            ],
            [
                'name' => 'Izgara Somon',
                'description' => 'Omega-3 açısından zengin protein kaynağı',
                'category' => 'dinner',
                'diet_type' => 'normal',
                'prep_time_minutes' => 10,
                'cook_time_minutes' => 15,
                'servings' => 2,
                'calories_per_serving' => 350,
                'protein' => 35.0,
                'carbs' => 5.0,
                'fat' => 20.0,
                'ingredients' => [
                    '2 somon fileto',
                    'Zeytinyağı',
                    'Limon',
                    'Tuz, karabiber',
                    'Taze dereotu'
                ],
                'instructions' => 'Somon filetolarını baharatlarla marine edin. Izgara tavada 6-8 dakika pişirin. Limon ve dereotu ile servis edin.',
                'difficulty' => 'medium',
                'is_popular' => true
            ],
            [
                'name' => 'Chia Pudingi',
                'description' => 'Fiber ve protein açısından zengin tatlı',
                'category' => 'dessert',
                'diet_type' => 'vegan',
                'prep_time_minutes' => 5,
                'cook_time_minutes' => 0,
                'servings' => 1,
                'calories_per_serving' => 180,
                'protein' => 6.0,
                'carbs' => 15.0,
                'fat' => 10.0,
                'ingredients' => [
                    '3 yemek kaşığı chia tohumu',
                    '1 su bardağı badem sütü',
                    '1 yemek kaşığı bal',
                    'Vanilya',
                    'Taze meyveler'
                ],
                'instructions' => 'Chia tohumlarını badem sütü ile karıştırın. Bal ve vanilya ekleyin. Buzdolabında 4 saat bekletin. Meyvelerle süsleyin.',
                'difficulty' => 'easy',
                'is_popular' => false
            ],
            [
                'name' => 'Fındık Ezmeli Smoothie',
                'description' => 'Protein açısından zengin atıştırmalık',
                'category' => 'snack',
                'diet_type' => 'vegetarian',
                'prep_time_minutes' => 3,
                'cook_time_minutes' => 0,
                'servings' => 1,
                'calories_per_serving' => 250,
                'protein' => 15.0,
                'carbs' => 20.0,
                'fat' => 12.0,
                'ingredients' => [
                    '1 muz',
                    '1 yemek kaşığı fındık ezmesi',
                    '1 su bardağı süt',
                    '1 tatlı kaşığı bal',
                    'Buz'
                ],
                'instructions' => 'Tüm malzemeleri blenderda karıştırın. Soğuk servis edin.',
                'difficulty' => 'easy',
                'is_popular' => false
            ]
        ];

        foreach ($recipes as $recipe) {
            Recipe::create($recipe);
        }
    }
}