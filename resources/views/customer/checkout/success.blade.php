@extends('dashboard')

@section('cust_content')
<div class="container mx-auto px-4 text-center">

    <div class="max-w-xl mx-auto bg-white shadow-2xl rounded-xl p-8 md:p-12 mt-10">
        <svg class="w-16 h-16 text-green-500 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>

        <h2 class="text-4xl font-extrabold text-gray-900 mb-3">Checkout Successfully!</h2>
        <p class="text-lg text-gray-600 mb-6">Thank you for shopping. Your order has been received and is being processed.</p>

        <div class="border border-green-200 bg-green-50 p-4 rounded-lg inline-block mb-8">
            <p class="text-xl font-bold text-green-700">Your Order Number: #{{ $order->id }}</p>
            <p class="text-sm text-green-600">Total Payment: Rp {{ number_format($order->total_price) }}</p>
        </div>

        <h3 class="text-xl font-semibold text-gray-800 mb-4">Next Step:</h3>
        <p class="text-gray-700">We will contact you soon for payment and shipping details.</p>

        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('customer.dashboard') }}"
                class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                Buy Another Items
            </a>
            {{-- <a href="{{ route('dashboard') }}"
                class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition">
                See Order
            </a> --}}
        </div>
    </div>
</div>
@endsection
