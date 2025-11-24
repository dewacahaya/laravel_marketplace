@extends('dashboard')

@section('cust_content')
    <div class="container mx-auto px-4 py-8 md:py-12">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex justify-between items-center mb-6 border-b pb-2">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Order Detail #{{ $order->id }}</h2>
            <a href="{{ route('customer.orders.index') }}"
                class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
                &larr; Back to Order List
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-6 mb-8 border-t-4 border-indigo-600">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Status --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Order Status</p>
                    @php
                        $statusClass = match ($order->status) {
                            'pending' => 'bg-yellow-500',
                            'processed' => 'bg-blue-500',
                            'shipped' => 'bg-indigo-500',
                            'completed' => 'bg-green-500',
                            'cancelled' => 'bg-red-500',
                            default => 'bg-gray-500',
                        };
                    @endphp
                    <span
                        class="mt-1 inline-flex items-center px-4 py-2 rounded-full text-lg font-bold text-white {{ $statusClass }} capitalize">
                        {{ $order->status }}
                    </span>
                </div>

                {{-- Tanggal --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Order Date</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>

                {{-- Total Pembayaran --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Payment</p>
                    <p class="text-3xl font-extrabold text-gray-900">Rp {{ number_format($order->total_price) }}</p>
                </div>

            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Kolom Kiri: Detail Item & Review Form --}}
            <div class="w-full lg:w-2/3">
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <h3 class="text-xl font-semibold text-gray-800 p-4 border-b">Detail Item ({{ $order->items->count() }}Product)</h3>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                    Product
                                </th>
                                <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unit Price
                                </th>
                                <th class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal
                                </th>
                                <th
                                    class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Review
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <tr class="hover:bg-gray-50">
                                    {{-- Produk --}}
                                    <td class="p-4 text-sm font-medium text-gray-900">
                                        {{ $item->product->name ?? 'Produk Dihapus' }}
                                    </td>
                                    {{-- Qty --}}
                                    <td class="p-4 whitespace-nowrap text-sm text-center text-gray-700">
                                        {{ $item->quantity }}
                                    </td>
                                    {{-- Harga Satuan --}}
                                    <td class="p-4 whitespace-nowrap text-sm text-right text-gray-700">
                                        Rp {{ number_format($item->price) }}
                                    </td>
                                    {{-- Subtotal --}}
                                    <td class="p-4 whitespace-nowrap text-base font-semibold text-right text-indigo-600">
                                        Rp {{ number_format($item->price * $item->quantity) }}
                                    </td>

                                    {{-- Review / Aksi --}}
                                    <td class="p-4 whitespace-nowrap text-center text-sm">
                                        @if ($order->status === 'completed')
                                            @if (isset($existingReviews[$item->product_id]))
                                                <span class="text-green-600 font-semibold">Reviewed</span>
                                            @else
                                                <button type="button"
                                                    onclick="document.getElementById('review-form-{{ $item->product_id }}').classList.remove('hidden')"
                                                    class="bg-indigo-500 text-white px-3 py-1 rounded-md hover:bg-indigo-600 transition">
                                                    Give Review
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-gray-500">Waiting</span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Inline Review Form --}}
                                @if ($order->status === 'completed' && !isset($existingReviews[$item->product_id]))
                                    <tr id="review-form-{{ $item->product_id }}" class="hidden bg-indigo-50/50">
                                        <td colspan="5" class="p-4 border-t border-indigo-200">
                                            <form action="{{ route('customer.orders.storeReview', $order->id) }}"
                                                method="POST" class="space-y-3">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                                                <div class="flex items-center space-x-4">
                                                    <label class="font-medium text-gray-700 w-32 text-left">Rating
                                                        (1-10)</label>
                                                    <input type="number" name="rating" min="1" max="10"
                                                        required class="border rounded-md p-2 w-20 text-center dark:text-black"
                                                        value="">
                                                    <span class="text-sm text-gray-500">⭐ Stars</span>
                                                </div>

                                                <div>
                                                    <label class="font-medium text-gray-700 block mb-1">Comment</label>
                                                    <textarea name="comment" rows="2" placeholder="Tuliskan komentar Anda tentang produk ini (opsional)"
                                                        class="border rounded-md p-2 w-full resize-none dark:text-black"></textarea>
                                                </div>

                                                <div class="text-right">
                                                    <button type="submit"
                                                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                                                        Send Review
                                                    </button>
                                                    <button type="button"
                                                        onclick="document.getElementById('review-form-{{ $item->product_id }}').classList.add('hidden')"
                                                        class="ml-2 text-gray-600 px-4 py-2 rounded-md hover:bg-gray-200 transition">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kolom Kanan: Informasi Pengiriman & Rincian Pembayaran --}}
            <div class="w-full lg:w-1/3 space-y-6">

                {{-- Detail Pengiriman --}}
                <div class="bg-white shadow-xl rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">Shipping Address</h3>
                    <p class="font-medium text-gray-900">Ordered By: {{ $customerProfile['name'] }}</p>
                    <p class="text-gray-700">Address: {{ $customerProfile['address'] }}</p>
                    <p class="text-gray-700 mt-2">Phone: {{ $customerProfile['phone'] }}</p>
                </div>

                {{-- Rincian Pembayaran --}}
                <div class="bg-gray-100 shadow-xl rounded-lg p-6 border-t-4 border-indigo-400">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Payment Detail</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>Total Price:</span>
                            <span>Rp {{ number_format($order->total_price) }}</span>
                        </div>
                    </div>
                    <div class="pt-4 mt-4 border-t border-gray-300">
                        <div class="flex justify-between text-xl font-bold text-gray-900">
                            <span>GRAND TOTAL:</span>
                            <span class="text-indigo-700">Rp {{ number_format($order->total_price) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
