<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Services\Customer\OrderService;
use App\Models\CustomerProfile;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->middleware(['auth', 'role:customer']);
        $this->service = $service;
    }

    public function confirm()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart is empty.');
        }

        $total = collect($cart)->sum('subtotal');

        // Load customer's current profile if exists
        // Catatan: Jika relasi customerProfile belum ada, ini akan error.
        $profile = Auth::user()->customerProfile;

        return view('customer.checkout.confirm', compact('cart', 'total', 'profile'));
    }

    // Process checkout (create order)
    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:1000',
        ]);

        // 1. UPDATE DATA DI USERS (Nama)
        Auth::user()->update(['name' => $request->name]);

        // 2. UPDATE/CREATE CUSTOMER PROFILE (Phone & Address)
        $profile = Auth::user()->customerProfile;
        if ($profile) {
            $profile->update([
                // 'user_id' tidak perlu di-update di sini
                // 'name' tidak di customer_profiles, tapi di users
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        } else {
            CustomerProfile::create([
                'user_id' => Auth::id(),
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        }

        // 3. DELEGATE ORDER CREATION TO SERVICE
        try {
            // KIRIM SEMUA DATA PENGIRIMAN KE SERVICE
            $order = $this->service->createOrderFromSession([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                // 'note' => $request->note, // jika ada
            ]);
        } catch (\Throwable $e) {
            Log::error("Order creation failed: " . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('customer.checkout.success', $order->id);
    }

    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('customer.checkout.success', compact('order'));
    }

    public function index()
    {
        $orders = $this->service->getCustomerOrders();

        return view('customer.orders.index', compact('orders'));
    }

    public function show(int $orderId)
    {
        try {
            $data = $this->service->getCustomerOrderDetailWithReviews($orderId);
            return view('customer.orders.show', $data);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('customer.orders.index')->with('error', 'Pesanan tidak ditemukan.');
        }
    }

    /**
     * Menyimpan ulasan baru untuk produk dalam pesanan.
     */
    public function storeReview(Request $request, int $orderId)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'rating' => 'required|integer|min:1|max:10', // Disesuaikan dengan max 10 dari Blade
            'comment' => 'nullable|string|max:500',
        ]);

        $dataToStore = $request->only(['product_id', 'rating', 'comment']);

        try {
            // Memanggil Service untuk menyimpan ulasan
            $this->service->storeReview($orderId, $dataToStore);

            return redirect()->back()->with('success', 'Ulasan berhasil dikirimkan! Terima kasih atas masukan Anda.');

        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::error("Review attempt failed: Order #{$orderId} not found or access denied.");
            return redirect()->back()->with('error', 'Gagal menyimpan ulasan: Pesanan tidak valid.');
        } catch (\Exception $e) {
            Log::critical("CRITICAL DB FAILURE on Review Save for Order #{$orderId}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan ulasan: Terjadi kesalahan database.');
        }
    }
}
