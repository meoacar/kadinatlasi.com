<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function getRecipes(Request $request)
    {
        $query = Recipe::query()->active();

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('diet_type')) {
            $query->byDietType($request->diet_type);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $recipes = $query->orderBy('name')->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $recipes
        ]);
    }

    public function getRecipe($id)
    {
        $recipe = Recipe::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $recipe
        ]);
    }

    public function getCategories()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'recipe_categories' => [
                    ['value' => 'Kahvaltı', 'label' => 'Kahvaltı'],
                    ['value' => 'Öğle Yemeği', 'label' => 'Öğle Yemeği'],
                    ['value' => 'Akşam Yemeği', 'label' => 'Akşam Yemeği'],
                    ['value' => 'Ara Öğün', 'label' => 'Ara Öğün'],
                    ['value' => 'Tatlı', 'label' => 'Tatlı'],
                ],
                'diet_types' => [
                    ['value' => 'vegan', 'label' => 'Vegan'],
                    ['value' => 'vegetarian', 'label' => 'Vejetaryen'],
                    ['value' => 'gluten-free', 'label' => 'Glutensiz'],
                    ['value' => 'dairy-free', 'label' => 'Sütsüz'],
                    ['value' => 'low-carb', 'label' => 'Düşük Karbonhidrat'],
                    ['value' => 'keto', 'label' => 'Ketojenik'],
                    ['value' => 'paleo', 'label' => 'Paleo'],
                ]
            ]
        ]);
    }
}