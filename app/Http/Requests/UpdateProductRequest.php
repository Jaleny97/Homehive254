<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->id === $this->product->seller_id;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0.01',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'sometimes|exists:categories,id',
            'quantity' => 'sometimes|integer|min:0',
            'image_url' => 'nullable|url',
            'is_active' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
        ];
    }
}
