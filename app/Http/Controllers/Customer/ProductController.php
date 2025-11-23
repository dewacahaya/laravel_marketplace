<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'reviews'])
            ->where('slug', $slug)
            ->firstOrFail();
        $averageRating = round($product->average_rating ?? 0, 1);

        return view('customer.products.show', compact('product', 'averageRating'));
    }
}
