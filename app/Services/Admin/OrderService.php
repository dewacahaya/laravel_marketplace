<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

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
}
