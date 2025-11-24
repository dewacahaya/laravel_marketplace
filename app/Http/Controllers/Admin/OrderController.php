<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $orders = $this->service->getAllOrders();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = $this->service->getOrderById($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, int $orderId): RedirectResponse
    {
        // 1. Validasi Status (Harus sesuai ENUM)
        $validated = $request->validate([
            'status' => 'required|string|in:pending,paid,shipped,completed,cancelled',
        ]);

        $newStatus = $validated['status'];

        try {
            $success = $this->service->updateOrderStatus($orderId, $newStatus);

            if ($success) {
                return back()->with('success', "Status pesanan #{$orderId} berhasil diperbarui menjadi '{$newStatus}'.");
            } else {
                return back()->with('error', 'Status pesanan tidak berubah atau tidak valid.');
            }
        } catch (\Exception $e) {
            Log::error("Admin update order status failed: " . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui status: Terjadi kesalahan server.');
        }
    }
}
