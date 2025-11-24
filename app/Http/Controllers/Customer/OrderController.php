<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Services\Customer\OrderService;
use App\Models\CustomerProfile;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        Auth::user()->update(['name' => $request->name]);

        $profileData = $request->only(['phone', 'address']);
        $profile = Auth::user()->customerProfile;

        if ($profile) {
            $profile->update($profileData);
        } else {
            CustomerProfile::create(array_merge(['user_id' => Auth::id()], $profileData));
        }

        try {
            $order = $this->service->createOrderFromSession([]);
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
