<?php

namespace App\Observers;

use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the OrderItems "created" event.
     */
    public function created(OrderItem $orderItem)
    {
        $product = $orderItem->product;

        // Pastikan qty numeric dan positif
        $quantity = $orderItem->quantity;

        if (!is_numeric($quantity)) {
            Log::error('OrderObserver: non-numeric quantity', [
                'order_item_id' => $orderItem->id,
                'quantity' => $quantity,
                'created_at' => $orderItem->created_at,
            ]);
            return;
        }

        $quantity = (int) $quantity;
        if ($quantity <= 0) {
            return;
        }

        if ($product && $quantity > 0) {
            $product->decrement('stock', $quantity);
        }
    }

    /**
     * Handle the OrderItem "updated" event.
     */
    public function updated(OrderItem $orderItems): void
    {
        //
    }

    /**
     * Handle the OrderItem "deleted" event.
     */
    public function deleted(OrderItem $orderItem)
    {
        $product = $orderItem->product;

        if ($product) {
            // Kembalikan stok kalau order dihapus
            $product->increment('stock', $orderItem->qty);
        }
    }

    /**
     * Handle the OrderItem "restored" event.
     */
    public function restored(OrderItem $orderItems): void
    {
        //
    }

    /**
     * Handle the OrderItem "force deleted" event.
     */
    public function forceDeleted(OrderItem $orderItems): void
    {
        //
    }
}
