<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * Get product reviews
     */
    public function index(Product $product): JsonResponse
    {
        $reviews = $product->reviews()
            ->with('user')
            ->where('is_verified_purchase', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($reviews, 200);
    }

    /**
     * Create review
     */
    public function store(StoreReviewRequest $request, Product $product): JsonResponse
    {
        // Check if user has purchased this product
        $hasPurchased = auth()->user()->orders()
            ->whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->where('payment_status', 'completed')
            ->exists();

        // Check if already reviewed
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'You have already reviewed this product',
            ], 400);
        }

        $review = Review::create([
            ...$request->validated(),
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'is_verified_purchase' => $hasPurchased,
        ]);

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review->load('user'),
        ], 201);
    }

    /**
     * Update review
     */
    public function update(\App\Http\Requests\UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $this->authorize('update', $review);

        $review->update($request->validated());

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review,
        ], 200);
    }

    /**
     * Delete review
     */
    public function destroy(Review $review): JsonResponse
    {
        $this->authorize('delete', $review);

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully'], 200);
    }
}
