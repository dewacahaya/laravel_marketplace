<?php

namespace App\Services\Vendor;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected function getVendorProductIds(): array
    {
        return Product::where('vendor_id', Auth::id())
            ->pluck('id')
            ->toArray();
    }

    public function getRelevantOrders(): Collection
    {
        $vendorProductIds = $this->getVendorProductIds();

        if (empty($vendorProductIds)) {
            return new Collection();
        }

        $vendorOrderItems = OrderItem::whereIn('product_id', $vendorProductIds)
            ->get();

        $orderIds = $vendorOrderItems->pluck('order_id')->unique()->toArray();

        if (empty($orderIds)) {
            return new Collection();
        }

        $orders = Order::whereIn('id', $orderIds)
            ->with([
                'user',
                'items' => function ($query) use ($vendorProductIds) {
                    $query->whereIn('product_id', $vendorProductIds)
                        ->with('product');
                }
            ])
            ->latest()
            ->get();

        return $orders;
    }

    public function getOrderDetail(int $orderId): ?array
    {
        $vendorProductIds = $this->getVendorProductIds();

        if (empty($vendorProductIds)) {
            return null;
        }

        $hasRelevantItems = OrderItem::where('order_id', $orderId)
            ->whereIn('product_id', $vendorProductIds)
            ->exists();

        if (!$hasRelevantItems) {
            return null;
        }

        $order = Order::with([
            'user.customerProfile',
            'items' => function ($query) use ($vendorProductIds) {
                $query->whereIn('product_id', $vendorProductIds)
                    ->with('product');
            }
        ])->find($orderId);

        if (!$order) {
            return null;
        }

        $profile = $order->user->customerProfile;

        $custProfile = [
            'name' => $order->user->name ?? 'N/A',
            'phone' => $profile->phone ?? 'N/A',
            'address' => $profile->address ?? 'Alamat belum diatur',
        ];

        return [
            'order' => $order,
            'customerProfile' => $custProfile,
        ];
    }

    public function updateOrderStatus(int $orderId, string $newStatus): bool
    {
        $vendorProductIds = $this->getVendorProductIds();
        if (empty($vendorProductIds)) {
            return false;
        }

        $order = Order::find($orderId);
        if (!$order) {
            return false;
        }

        $hasRelevantItems = OrderItem::where('order_id', $orderId)
            ->whereIn('product_id', $vendorProductIds)
            ->exists();
        if (!$hasRelevantItems) {
            return false;
        }

        $validStatuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            Log::warning("Vendor tried to set invalid status: {$newStatus} for Order #{$orderId}");
            return false;
        }

        if ($order->status !== $newStatus) {
            $order->status = $newStatus;

            return $order->save();
        }

        return true;
    }
}
