<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    /**
     * Get user's wishlist
     */
    public function index(): JsonResponse
    {
        $wishlist = auth()->user()->wishlist()
            ->with('product')
            ->paginate(15);

        return response()->json($wishlist, 200);
    }

    /**
     * Add product to wishlist
     */
    public function store(Product $product): JsonResponse
    {
        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($exists) {
            return response()->json([
                'message' => 'Product already in wishlist',
            ], 400);
        }

        $wishlist = Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return response()->json([
            'message' => 'Product added to wishlist',
            'wishlist' => $wishlist->load('product'),
        ], 201);
    }

    /**
     * Remove product from wishlist
     */
    public function destroy(Product $product): JsonResponse
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        return response()->json(['message' => 'Product removed from wishlist'], 200);
    }

    /**
     * Clear entire wishlist
     */
    public function clear(): JsonResponse
    {
        auth()->user()->wishlist()->delete();

        return response()->json(['message' => 'Wishlist cleared'], 200);
    }
}
