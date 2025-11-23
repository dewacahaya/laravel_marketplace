<?php

namespace App\Services\Customer;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\OrderCreated;
use Exception;

class OrderService
{
    public function createOrderFromSession(array $customerData = [])
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            throw new \RuntimeException('Cart is empty.');
        }

        // Build items list with fresh product data
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

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'status' => 'pending', // default
            ]);

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

    public function getCustomerOrders()
    {
        return Order::where('user_id', auth()->id())
            ->with('items') // Muat OrderItems untuk menampilkan ringkasan di daftar
            ->latest() // Urutkan dari yang terbaru
            ->get();
    }

    public function getCustomerOrderDetail(int $orderId)
    {
        return Order::where('user_id', auth()->id())
            ->with('items.product') // Muat items dan detail product terkait
            ->findOrFail($orderId);
    }
}
