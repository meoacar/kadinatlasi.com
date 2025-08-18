<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SecondHandProduct;
use App\Models\SecondHandCategory;
use App\Models\SecondHandMessage;
use App\Models\SecondHandFavorite;
use App\Models\SecondHandReview;
use Illuminate\Http\Request;

class SecondHandController extends Controller
{
    public function index(Request $request)
    {
        $query = SecondHandProduct::query();
        $user = auth('sanctum')->user();

        // Show different products based on user role
        if ($user) {
            if ($user->hasAnyRole(['admin', 'super_admin'])) {
                // Admin sees all products
            } else {
                // Regular users see active products + their own pending products
                $query->where(function($q) use ($user) {
                    $q->where('status', 'active')
                      ->orWhere(function($subQ) use ($user) {
                          $subQ->where('user_id', $user->id)
                               ->whereIn('status', ['pending', 'active']);
                      });
                });
            }
        } else {
            // Guests only see active products
            $query->where('status', 'active');
        }

        // Filter by status for admin
        if ($request->has('status') && $user && $user->hasRole('admin')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by condition
        if ($request->has('condition') && $request->condition) {
            $query->where('condition', $request->condition);
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['success' => true, 'data' => $products]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Check user's listing limit
        if (!$this->canCreateListing($user)) {
            $membershipType = $user->premium_subscription->plan->name ?? 'Normal';
            $limit = $this->getListingLimit($user->premium_subscription->plan->slug ?? 'free');
            $currentCount = $this->getUserListingCount($user->id);
            
            return response()->json([
                'success' => false,
                'message' => "Bu ay ilan verme limitinizi doldurdunuz. {$membershipType} üyeliğinizle ayda maksimum {$limit} ilan verebilirsiniz. Mevcut: {$currentCount}/{$limit}"
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'condition' => 'required|in:excellent,good,fair,poor',
            'category' => 'required|string',
            'location' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePaths = [];
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('second-hand-products', 'public');
                $imagePaths[] = $path;
            }
        }

        $product = SecondHandProduct::create([
            'title' => $request->title,
            'name' => $request->title,
            'slug' => \Str::slug($request->title),
            'price' => $request->price,
            'original_price' => $request->original_price,
            'condition' => $request->condition,
            'category' => $request->category,
            'location' => $request->location,
            'description' => $request->description,
            'seller_name' => auth()->user()->name ?? 'Anonim Kullanıcı',
            'seller_email' => auth()->user()->email ?? 'anonim@example.com',
            'seller_phone' => $request->seller_phone ?? '',
            'seller_id' => $user->id, // seller_id ekle
            'images' => $imagePaths,
            'status' => 'pending',
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ürün başarıyla eklendi ve onay bekliyor',
            'data' => $product
        ]);
    }

    public function show($id)
    {
        $product = SecondHandProduct::findOrFail($id);
        $user = auth('sanctum')->user();

        // Show product if: active, or user is admin, or user is the owner
        if ($user) {
            $canView = $product->status === 'active' || 
                      $user->hasAnyRole(['admin', 'super_admin']) || 
                      $product->user_id === $user->id;
            
            if (!$canView) {
                return response()->json(['success' => false, 'message' => 'Ürün bulunamadı'], 404);
            }
        } else {
            // Guests only see active products
            if ($product->status !== 'active') {
                return response()->json(['success' => false, 'message' => 'Ürün bulunamadı'], 404);
            }
        }

        return response()->json(['success' => true, 'data' => $product]);
    }

    public function update(Request $request, $id)
    {
        $product = SecondHandProduct::findOrFail($id);
        
        $request->validate([
            'status' => 'sometimes|in:active,pending,rejected,sold'
        ]);

        $product->update($request->only(['status']));

        return response()->json([
            'success' => true,
            'message' => 'Ürün durumu güncellendi',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = SecondHandProduct::findOrFail($id);
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ürün silindi'
        ]);
    }

    public function getCategories()
    {
        $categories = SecondHandCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($category) {
                return [
                    'value' => $category->slug,
                    'label' => $category->name,
                    'icon' => $category->icon,
                    'description' => $category->description
                ];
            });

        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function getStats()
    {
        $totalProducts = SecondHandProduct::count();
        $activeProducts = SecondHandProduct::where('status', 'active')->count();
        $pendingProducts = SecondHandProduct::where('status', 'pending')->count();
        $soldProducts = SecondHandProduct::where('status', 'sold')->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_products' => $totalProducts,
                'active_products' => $activeProducts,
                'pending_products' => $pendingProducts,
                'sold_products' => $soldProducts,
                'total_categories' => 10
            ]
        ]);
    }

    public function getListingLimits()
    {
        $user = auth()->user();
        
        $membershipType = $user->premium_subscription->plan->slug ?? 'free';
        $membershipName = $user->premium_subscription->plan->name ?? 'Normal';
        $limit = $this->getListingLimit($membershipType);
        $used = $this->getUserListingCount($user->id);
        $remaining = max(0, $limit - $used);
        $canCreate = $this->canCreateListing($user);
        
        return response()->json([
            'success' => true,
            'data' => [
                'membership_type' => $membershipName,
                'limit' => $limit,
                'used' => $used,
                'remaining' => $remaining,
                'can_create' => $canCreate,
                'month' => now()->format('F Y')
            ]
        ]);
    }

    private function getListingLimit($membershipType): int
    {
        return match($membershipType) {
            'basic' => 5,
            'premium' => 10,
            'vip' => 15,
            default => 1 // free/normal users
        };
    }

    private function getUserListingCount($userId): int
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        return SecondHandProduct::where('user_id', $userId)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
    }

    private function canCreateListing($user): bool
    {
        $membershipType = $user->premium_subscription->plan->slug ?? 'free';
        $limit = $this->getListingLimit($membershipType);
        $currentCount = $this->getUserListingCount($user->id);

        return $currentCount < $limit;
    }

    // Messages
    public function getMessages($productId)
    {
        $product = SecondHandProduct::findOrFail($productId);
        $user = auth()->user();
        
        // Only seller and interested buyers can see messages
        $messages = SecondHandMessage::where('product_id', $productId)
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['success' => true, 'data' => $messages]);
    }

    public function sendMessage(Request $request, $productId)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $product = SecondHandProduct::findOrFail($productId);
        $sender = auth()->user();
        
        if ($sender->id === $product->user_id) {
            return response()->json(['success' => false, 'message' => 'Kendi ürününüze mesaj gönderemezsiniz'], 400);
        }

        $message = SecondHandMessage::create([
            'product_id' => $productId,
            'sender_id' => $sender->id,
            'receiver_id' => $product->user_id,
            'message' => $request->message
        ]);

        $message->load(['sender', 'receiver']);

        return response()->json(['success' => true, 'data' => $message]);
    }

    // Favorites
    public function toggleFavorite($productId)
    {
        $user = auth()->user();
        $favorite = SecondHandFavorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
        } else {
            SecondHandFavorite::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            $isFavorited = true;
        }

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited
        ]);
    }

    public function getFavorites()
    {
        $user = auth()->user();
        $favorites = SecondHandProduct::whereHas('favorites', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'active')->get();

        return response()->json(['success' => true, 'data' => $favorites]);
    }

    // Reviews
    public function addReview(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        $product = SecondHandProduct::findOrFail($productId);
        $reviewer = auth()->user();
        
        if ($reviewer->id === $product->user_id) {
            return response()->json(['success' => false, 'message' => 'Kendi ürününüzü değerlendiremezsiniz'], 400);
        }

        $existingReview = SecondHandReview::where('product_id', $productId)
            ->where('reviewer_id', $reviewer->id)
            ->first();

        if ($existingReview) {
            return response()->json(['success' => false, 'message' => 'Bu ürünü zaten değerlendirdiniz'], 400);
        }

        $review = SecondHandReview::create([
            'product_id' => $productId,
            'reviewer_id' => $reviewer->id,
            'seller_id' => $product->user_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        $review->load(['reviewer']);

        return response()->json(['success' => true, 'data' => $review]);
    }

    public function getReviews($productId)
    {
        $reviews = SecondHandReview::where('product_id', $productId)
            ->with(['reviewer'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $reviews]);
    }

    // User profile
    public function getUserProfile($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        $products = SecondHandProduct::where('user_id', $userId)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $reviews = SecondHandReview::where('seller_id', $userId)
            ->with(['reviewer', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $averageRating = $reviews->avg('rating') ?: 0;
        $totalReviews = $reviews->count();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'created_at' => $user->created_at,
                    'avatar' => $user->avatar
                ],
                'products' => $products,
                'reviews' => $reviews,
                'average_rating' => round($averageRating, 1),
                'total_reviews' => $totalReviews,
                'total_products' => $products->count()
            ]
        ]);
    }
}