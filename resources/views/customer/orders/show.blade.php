@extends('dashboard')

@section('cust_content')
    <div class="container mx-auto px-4 py-8 md:py-12">

        <div class="flex justify-between items-center mb-6 border-b pb-2">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Order Detail #{{ $order->id }}</h2>
            <a href="{{ route('customer.orders.index') }}"
                class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
                &larr; Back to Order List
            </a>
        </div>

        {{-- Header dan Status Pesanan --}}
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

            {{-- Kolom Kiri: Detail Item --}}
            <div class="w-full lg:w-2/3">
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <h3 class="text-xl font-semibold text-gray-800 p-4 border-b">Order Items ({{ $order->items->count() }} Product)</h3>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">
                                    Product</th>
                                <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty
                                </th>
                                <th class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit
                                    Price</th>
                                <th class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <tr class="hover:bg-gray-50">
                                    {{-- Produk --}}
                                    <td class="p-4 whitespace-nowrap text-sm font-medium text-gray-900">
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
                                </tr>
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
                    <p class="font-medium text-gray-900">{{ $order->name ?? 'N/A' }}</p>
                    <p class="text-gray-700">{{ $order->address ?? 'Address not setted' }}</p>
                    <p class="text-gray-700 mt-2">Telp: {{ $order->phone ?? 'N/A' }}</p>

                    {{-- @if ($order->note)
                        <div class="mt-4 p-3 bg-yellow-50 rounded-md border border-yellow-200">
                            <p class="text-sm font-medium text-yellow-800">Catatan:</p>
                            <p class="text-sm text-yellow-700">{{ $order->note }}</p>
                        </div>
                    @endif --}}
                </div>

                {{-- Rincian Pembayaran --}}
                <div class="bg-gray-100 shadow-xl rounded-lg p-6 border-t-4 border-indigo-400">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Payment Detail</h3>

                    <div class="space-y-2">
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>Total Product Price:</span>
                            <span>Rp {{ number_format($order->total_price) }}</span>
                        </div>
                        {{-- Asumsi biaya kirim dan diskon bisa ditambahkan di sini --}}
                        {{-- <div class="flex justify-between text-sm text-gray-700">
                            <span>:</span>
                            <span>Rp {{ number_format(0) }}</span>
                        </div> --}}
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
