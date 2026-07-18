<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all products with filtering and search
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category', 'seller')
            ->where('is_active', true);

        // Search by name or description
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by price range
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by rating
        if ($request->min_rating) {
            $query->whereHas('reviews', function ($q) {
                $q->where('rating', '>=', request('min_rating'));
            });
        }

        // Sort
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate($request->per_page ?? 15);

        return response()->json($products, 200);
    }

    /**
     * Get featured products
     */
    public function featured(): JsonResponse
    {
        $products = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('category', 'seller')
            ->limit(10)
            ->get();

        return response()->json($products, 200);
    }

    /**
     * Get product by ID
     */
    public function show(Product $product): JsonResponse
    {
        if (!$product->is_active) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->load('category', 'seller', 'reviews.user');

        return response()->json($product, 200);
    }

    /**
     * Create product (Seller only)
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->authorize('isSeller');

        $product = Product::create([
            ...$request->validated(),
            'seller_id' => auth()->id(),
            'sku' => $this->generateSku(),
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    /**
     * Update product (Seller only)
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        $product->update($request->validated());

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ], 200);
    }

    /**
     * Delete product (Seller only)
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    /**
     * Generate unique SKU
     */
    private function generateSku(): string
    {
        return 'PROD-' . strtoupper(substr(uniqid(), -8));
    }
}
