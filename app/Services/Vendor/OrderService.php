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
            return new Collection(); // Return Collection kosong jika tidak ada produk
        }

        // 1. Ambil semua OrderItem yang terkait dengan produk Vendor.
        $vendorOrderItems = OrderItem::whereIn('product_id', $vendorProductIds)
            ->get();

        // 2. Ambil semua Order ID unik dari item-item tersebut.
        $orderIds = $vendorOrderItems->pluck('order_id')->unique()->toArray();

        if (empty($orderIds)) {
            return new Collection();
        }

        // 3. Muat objek Order lengkap, termasuk item-itemnya.
        // Kita hanya memuat item yang relevan untuk vendor ini.
        $orders = Order::whereIn('id', $orderIds)
            ->with([
                'user',
                'items' => function ($query) use ($vendorProductIds) {
                    // HANYA ambil item yang produknya milik vendor ini.
                    $query->whereIn('product_id', $vendorProductIds)
                        ->with('product');
                }
            ])
            ->latest()
            ->get();

        return $orders;
    }

    public function getOrderDetail(int $orderId): ?Order
    {
        $vendorProductIds = $this->getVendorProductIds();

        if (empty($vendorProductIds)) {
            return null;
        }

        // 1. Cek apakah ada OrderItem yang relevan untuk Order ID ini.
        $hasRelevantItems = OrderItem::where('order_id', $orderId)
            ->whereIn('product_id', $vendorProductIds)
            ->exists();

        if (!$hasRelevantItems) {
            return null; // Vendor tidak memiliki produk di pesanan ini.
        }

        // 2. Muat Order dengan item yang relevan (dan user).
        $order = Order::with([
            'user',
            'items' => function ($query) use ($vendorProductIds) {
                $query->whereIn('product_id', $vendorProductIds)
                    ->with('product');
            }
        ])->find($orderId);

        return $order;
    }

    public function updateOrderStatus(int $orderId, string $newStatus): bool
    {
        // Safety check 1: Vendor harus memiliki produk di order ini
        $vendorProductIds = $this->getVendorProductIds();
        if (empty($vendorProductIds)) {
            return false;
        }

        $order = Order::find($orderId);
        if (!$order) {
            return false;
        }

        // Safety check 2: Pastikan Vendor memiliki item di order ini
        $hasRelevantItems = OrderItem::where('order_id', $orderId)
            ->whereIn('product_id', $vendorProductIds)
            ->exists();
        if (!$hasRelevantItems) {
            return false;
        }

        // Validasi status (sesuai ENUM tabel orders)
        $validStatuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            Log::warning("Vendor tried to set invalid status: {$newStatus} for Order #{$orderId}");
            return false;
        }

        // Logika Update: Ubah status jika berbeda
        if ($order->status !== $newStatus) {
            $order->status = $newStatus;

            // Catatan: Pastikan kolom 'status' di model Order di-set sebagai $fillable
            return $order->save();
        }

        // Jika status sudah sama, anggap berhasil (tidak perlu update)
        return true;
    }
}
