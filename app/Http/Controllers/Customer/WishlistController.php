<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlist()->with('category')->paginate(10);
        return view('customer.products.wishlist', compact('wishlists'));
    }

    public function toggle($productId)
    {
        $user = auth()->user();

        if ($user->wishlist()->where('product_id', $productId)->exists()) {
            $user->wishlist()->detach($productId);
            return back()->with('success', 'Removed from wishlist.');
        }

        $user->wishlist()->attach($productId);
        return back()->with('success', 'Added to wishlist.');
    }
}

