<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->where('status', 'active')
            ->when($request->keyword, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%");
            })
            ->latest()
            ->paginate(12);

        return view('dashboard.customer', compact('products'));
    }

}
