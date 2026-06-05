<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ProductController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Show the product listing
    public function index(Request $request)
    {
        $products = Product::query()
            // FILTER: Now filtering by category instead of location
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            
            // UNNECESSARY: Commented out location filtering as per your request
            // ->when($request->location, fn($q) => $q->where('location', $request->location))
            
            ->when($request->max_price, fn($q) => $q->where('price', '<=', $request->max_price))
            ->latest()
            ->paginate(12);

        return view('catalog', compact('products'));
    }

    // Show the form
    public function create()
    {
        return view('add-product'); 
    }

    // Save the data to PostgreSQL
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string', // Added category validation
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category = $request->category; // Save category from form
        
        // Since all items are yours, we can hardcode location or remove it
        $product->location = "Nairobi"; 

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image_url = '/storage/' . $path;
        } else {
            $product->image_url = "https://via.placeholder.com/150";
        }

        $product->description = $request->description ?? "Airbnb Inventory Item";
        $product->save();

        return redirect('/')
            ->with('success', 'Product saved successfully!');
    }
}