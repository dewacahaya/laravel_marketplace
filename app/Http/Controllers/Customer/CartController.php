<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Customer\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): View
    {
        $cart = $this->cartService->getCart();
        $total = collect($cart)->sum('subtotal');
        return view('customer.cart.index', compact('cart', 'total'));
    }

    public function add(int $productId): RedirectResponse
    {
        $product = Product::findOrFail($productId);
        $this->cartService->addToCart($product);

        return redirect()->route('customer.cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $newQuantity = (int) $validated['quantity'];

        $this->cartService->updateQuantity($productId, $newQuantity);

        // Jika kuantitas di-set 0 atau melebihi stok, service sudah menanganinya
        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(int $productId): RedirectResponse
    {
        $this->cartService->removeItem($productId);

        return back()->with('success', 'Item removed.');
    }
}
