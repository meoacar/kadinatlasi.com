<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BeautyCategory;
use App\Models\BeautyArticle;
use App\Models\BeautyProduct;
use App\Models\BeautyVideo;
use App\Models\BeautyTip;
use App\Models\UserOutfit;
use Illuminate\Http\Request;

class BeautyController extends Controller
{
    public function categories()
    {
        $categories = BeautyCategory::where('is_active', true)
            ->orderBy('order')
            ->with(['articles' => function($query) {
                $query->where('is_active', true)->limit(3);
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function articles(Request $request)
    {
        $query = BeautyArticle::where('is_active', true);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $articles = $query->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    public function article($id)
    {
        $article = BeautyArticle::where('is_active', true)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }

    public function products(Request $request)
    {
        $query = BeautyProduct::where('is_active', true);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('rating', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function videos(Request $request)
    {
        $query = BeautyVideo::where('is_active', true);

        if ($request->featured) {
            $query->where('featured', true);
        }

        $videos = $query->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
    }

    public function tips(Request $request)
    {
        $query = BeautyTip::where('is_active', true);

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->featured) {
            $query->where('featured', true);
        }

        $tips = $query->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $tips
        ]);
    }

    public function outfits()
    {
        $outfits = UserOutfit::with('user')
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $outfits
        ]);
    }

    public function storeOutfit(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'season' => 'required|string',
            'image1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|array'
        ]);

        $data = $request->only(['title', 'description', 'category', 'season', 'tags']);
        $data['user_id'] = auth()->id();
        $data['is_approved'] = false;

        // Resimleri kaydet
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("image{$i}")) {
                $image = $request->file("image{$i}");
                $filename = time() . "_outfit_{$i}_" . $image->getClientOriginalName();
                $image->move(public_path('uploads/outfits'), $filename);
                $data["image{$i}"] = 'uploads/outfits/' . $filename;
            }
        }

        $outfit = UserOutfit::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Kombin başarıyla paylaşıldı! Onay bekliyor.',
            'data' => $outfit
        ]);
    }
}