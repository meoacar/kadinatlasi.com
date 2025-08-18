<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load(['items.product']);

        return response()->json($cart);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        $cart = $this->getOrCreateCart($request);
        $product = Product::findOrFail($request->product_id);
        $variant = null;
        
        if ($request->variant_id) {
            $variant = \App\Models\ProductVariant::findOrFail($request->variant_id);
            // Varyantın bu ürüne ait olduğunu kontrol et
            if ($variant->product_id !== $product->id) {
                return response()->json(['error' => 'Varyant bu ürüne ait değil'], 400);
            }
        }

        // Aynı ürün ve varyant kombinasyonunu kontrol et
        $existingItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        $price = $variant ? $variant->final_price : $product->final_price;

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
                'price' => $price
            ]);
        }

        $this->updateCartTotal($cart);

        return response()->json(['message' => 'Ürün sepete eklendi']);
    }

    public function updateItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getOrCreateCart($request);
        $item = $cart->items()->findOrFail($itemId);

        $item->update(['quantity' => $request->quantity]);
        $this->updateCartTotal($cart);

        return response()->json(['message' => 'Sepet güncellendi']);
    }

    public function removeItem(Request $request, $itemId)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->items()->findOrFail($itemId)->delete();
        $this->updateCartTotal($cart);

        return response()->json(['message' => 'Ürün sepetten kaldırıldı']);
    }

    private function getOrCreateCart(Request $request)
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }

        $sessionId = $request->session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    private function updateCartTotal(Cart $cart)
    {
        $total = $cart->items()->sum(\DB::raw('quantity * price'));
        $cart->update(['total_amount' => $total]);
    }
}