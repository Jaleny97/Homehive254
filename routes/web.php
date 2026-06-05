<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index']);
Route::get('/add-product', [ProductController::class, 'create'])->name('products.create');
Route::post('/add-product', [ProductController::class, 'store'])->name('products.store');