<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\Vendor\OrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Menampilkan daftar semua pesanan yang berisi produk Vendor ini.
     */
    public function index(): View
    {
        $orders = $this->orderService->getRelevantOrders();
        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan spesifik untuk produk Vendor ini.
     */
    public function show(int $orderId): View
    {
        $data = $this->orderService->getOrderDetail($orderId);
        if (!$data) {
            abort(404, 'Pesanan tidak ditemukan atau Anda tidak memiliki akses ke detail pesanan ini.');
        }
        return view('vendor.orders.show', $data);
    }

    public function updateStatus(Request $request, int $orderId)
    {
        // 1. Validasi Status (Berdasarkan ENUM di tabel Orders)
        $validated = $request->validate([
            'status' => 'required|string|in:pending,paid,shipped,completed,cancelled',
        ]);

        $newStatus = $validated['status'];

        // 2. Panggil service untuk mengupdate status
        $success = $this->orderService->updateOrderStatus($orderId, $newStatus);

        // 3. Muat ulang order untuk pesan feedback yang akurat
        $order = $this->orderService->getOrderDetail($orderId);

        if ($success && $order && $order->status === $newStatus) {
            return back()->with('success', "Status Pesanan #{$orderId} berhasil diubah menjadi '{$newStatus}'.");
        } elseif ($success && $order) {
            // Status berhasil di-save, tetapi tidak berubah (berarti status sudah sama)
            return back()->with('success', "Status Pesanan #{$orderId} sudah berstatus '{$order->status}'. Tidak ada perubahan yang dilakukan.");
        } else {
            return back()->with('error', 'Gagal memperbarui status pesanan. Pesanan tidak ditemukan atau Anda tidak berhak mengubahnya.');
        }
    }
}
