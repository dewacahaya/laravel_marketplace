@extends('dashboard')

@section('cust_content')
    <div class="container mx-auto px-4 md:py-12">

        <h2 class="text-3xl font-extrabold dark:text-white text-gray-900 mb-6 border-b pb-2">🧾 Order History</h2>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($orders->isEmpty())
            {{-- Jika daftar pesanan kosong --}}
            <div class="text-center py-10 border border-dashed border-gray-300 rounded-lg bg-gray-50 mt-10">
                <p class="text-xl text-gray-500">You don't have any order history yet.</p>
                <a href="{{ route('customer.products') }}"
                    class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium transition">
                    Start Shopping Now!
                </a>
            </div>
        @else
            {{-- Jika ada pesanan, tampilkan dalam tabel --}}
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID
                            </th>
                            <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                            </th>
                            <th
                                class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Total Item</th>
                            <th class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                                Price</th>
                            <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                            </th>
                            <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                {{-- Order ID --}}
                                <td class="p-4 whitespace-nowrap text-sm font-semibold text-indigo-600">
                                    #{{ $order->id }}
                                </td>

                                {{-- Tanggal --}}
                                <td class="p-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>

                                {{-- Jumlah Item --}}
                                <td class="p-4 whitespace-nowrap text-sm text-gray-700 hidden sm:table-cell">
                                    {{ $order->items->sum('quantity') }}
                                </td>

                                {{-- Total Harga --}}
                                <td class="p-4 whitespace-nowrap text-base font-bold text-right text-gray-900">
                                    Rp {{ number_format($order->total_price) }}
                                </td>

                                {{-- Status --}}
                                <td class="p-4 whitespace-nowrap text-center text-black">
                                    @php
                                        $statusClass = match ($order->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-900',
                                            'processed' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-indigo-100 text-indigo-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusClass }} capitalize">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="p-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('customer.orders.show', $order->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-semibold transition duration-150">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
