<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    /**
     * Create
     */
    public function create(User $user): bool
    {
        return $user->isSeller();
    }

    /**
     * Update
     */
    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->seller_id || $user->isAdmin();
    }

    /**
     * Delete
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->seller_id || $user->isAdmin();
    }
}
