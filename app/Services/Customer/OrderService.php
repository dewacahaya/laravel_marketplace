<?php

namespace App\Services\Customer;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review; // Import model Review
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class OrderService
{
    public function createOrderFromSession(array $customerData = [])
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            throw new \RuntimeException('Cart is empty.');
        }

        $items = [];
        foreach ($cart as $id => $entry) {
            $product = Product::find($id);
            if (!$product) {
                throw new \RuntimeException("Product #{$id} not found.");
            }

            // price from product (single source of truth)
            $price = (float) $product->price;
            $qty = (int) $entry['quantity'];

            if ($product->stock < $qty) {
                throw new \RuntimeException("Insufficient stock for product: {$product->name}.");
            }

            $items[] = [
                'product' => $product,
                'quantity' => $qty,
                'price' => $price,
                'subtotal' => $price * $qty,
            ];
        }

        $totalPrice = collect($items)->sum('subtotal');

        // Use transaction to create order & items
        $order = DB::transaction(function () use ($items, $totalPrice, $customerData) {

            $orderCreationData = [
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'status' => 'pending',
                // PASTIKAN KOLOM INI ADA DI SKEMA TABEL ORDERS ANDA
                'name' => $customerData['name'] ?? Auth::user()->name,
                'phone' => $customerData['phone'] ?? null,
                'address' => $customerData['address'] ?? null,
            ];

            $order = Order::create($orderCreationData);

            foreach ($items as $it) {
                $order->items()->create([
                    'product_id' => $it['product']->id,
                    'quantity' => $it['quantity'],
                    'price' => $it['price'],
                ]);
            }

            return $order;
        });

        // clear session cart
        session()->forget('cart');

        return $order;
    }

    public function getCustomerOrders(): Collection
    {
        if (!Auth::check()) {
            return new Collection();
        }

        return Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->get();
    }

    public function getCustomerOrderDetailWithReviews(int $orderId): array
    {
        if (!Auth::check()) {
            throw new ModelNotFoundException("User not authenticated.");
        }

        $order = Order::where('user_id', Auth::id()) // Akses langsung model Order
            ->with('items.product')
            ->findOrFail($orderId);

        $productIds = $order->items->pluck('product_id');

        // Ambil review yang sudah ada [product_id => review_id]
        $existingReviews = Review::where('user_id', Auth::id())
            ->whereIn('product_id', $productIds)
            ->pluck('id', 'product_id');

        return [
            'order' => $order,
            'existingReviews' => $existingReviews,
        ];
    }

    public function storeReview(int $orderId, array $data): void
    {
        if (!Auth::check()) {
            throw new \RuntimeException('Authentication required to store review.');
        }

        $order = Order::where('user_id', Auth::id())->findOrFail($orderId); // Akses langsung model Order
        $productId = $data['product_id'];

        if ($order->status !== 'completed') {
            throw new \RuntimeException('Ulasan hanya dapat diberikan jika pesanan telah selesai (completed).');
        }

        if (!$order->items->contains('product_id', $productId)) {
            throw new \RuntimeException('Produk tidak ditemukan dalam pesanan ini.');
        }

        $alreadyReviewed = Review::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        if ($alreadyReviewed) {
            throw new \RuntimeException('Anda sudah memberikan ulasan untuk produk ini. Setiap pelanggan hanya diperbolehkan memberikan satu ulasan per produk.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);
    }
}
