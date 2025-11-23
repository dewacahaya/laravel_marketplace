<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Services\Customer\OrderService;
use App\Models\CustomerProfile;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->middleware(['auth', 'role:customer']);
        $this->service = $service;
    }

    // show confirmation form: cart + form for customer data
    public function confirm()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart is empty.');
        }

        $total = collect($cart)->sum('subtotal');

        // Load customer's current profile if exists
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
            // 'note' => 'nullable|string|max:1000',
        ]);

        // Save/update customer profile (we agreed profiles stored separately)
        $profile = Auth::user()->customerProfile;
        if ($profile) {
            $profile->update([
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

        // Delegate order creation to service
        try {
            $order = $this->service->createOrderFromSession([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                // 'note' => $request->note,
            ]);
        } catch (\Throwable $e) {
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

    public function show($id)
    {
        try {
            $order = $this->service->getCustomerOrderDetail($id);

            return view('customer.orders.show', compact('order'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Pesanan tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }
}
