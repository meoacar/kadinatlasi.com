<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;

class HeroSliderController extends Controller
{
    public function index()
    {
        $sliders = HeroSlider::active()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $sliders
        ]);
    }
}