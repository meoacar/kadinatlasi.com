<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportResource;
use Illuminate\Http\Request;

class SupportResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportResource::active()->orderBy('sort_order')->orderBy('title');

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('emergency')) {
            $query->emergency();
        }

        $resources = $query->get();

        return response()->json([
            'success' => true,
            'data' => $resources
        ]);
    }

    public function show(SupportResource $supportResource)
    {
        return response()->json([
            'success' => true,
            'data' => $supportResource
        ]);
    }

    public function categories()
    {
        $categories = [
            [
                'key' => 'psychological',
                'name' => 'Psikolojik Destek',
                'icon' => '🧠',
                'description' => 'Ruh sağlığı ve psikolojik destek hizmetleri'
            ],
            [
                'key' => 'medical',
                'name' => 'Tıbbi Destek',
                'icon' => '🏥',
                'description' => 'Sağlık hizmetleri ve tıbbi danışmanlık'
            ],
            [
                'key' => 'legal',
                'name' => 'Hukuki Destek',
                'icon' => '⚖️',
                'description' => 'Hukuki danışmanlık ve yasal haklar'
            ],
            [
                'key' => 'social',
                'name' => 'Sosyal Destek',
                'icon' => '🤝',
                'description' => 'Sosyal hizmetler ve toplumsal destek'
            ],
            [
                'key' => 'emergency',
                'name' => 'Acil Durum',
                'icon' => '🚨',
                'description' => '7/24 acil yardım hatları'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}