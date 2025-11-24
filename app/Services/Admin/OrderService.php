<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function getAllOrders(array $filters = [])
    {
        $query = Order::with(['user'])
            ->orderByDesc('created_at');

        // Optional: filter by status
        // if (!empty($filters['status'])) {
        //     $query->where('status', $filters['status']);
        // }

        return $query->paginate(10);
    }

    public function getOrderById($id)
    {
        return Order::with([
            'user',
            'items.product'
        ])->findOrFail($id);
    }

    public function updateOrderStatus(int $orderId, string $newStatus): bool
    {
        $order = Order::find($orderId);
        if (!$order) {
            return false;
        }

        $validStatuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            Log::warning("Admin tried to set invalid status: {$newStatus} for Order #{$orderId}");
            return false;
        }

        if ($order->status !== $newStatus) {
            $order->status = $newStatus;

            return $order->save();
        }

        return true;
    }
}
